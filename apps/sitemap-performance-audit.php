<?php
// mwperformanceaudit.php - Sitemap-based performance auditing tool
// Analyzes page speed, Core Web Vitals, and optimization opportunities across sitemap URLs

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    // Validate sitemap URL
    if (empty($_POST['sitemap']) || !filter_var($_POST['sitemap'], FILTER_VALIDATE_URL)) {
        echo json_encode(['error' => 'Invalid or missing sitemap URL']);
        exit;
    }
    $sitemapUrl = $_POST['sitemap'];
    $maxPages = isset($_POST['max_pages']) ? intval($_POST['max_pages']) : 10;
    $maxPages = max(1, min($maxPages, 50)); // Limit between 1-50 pages

    // Fetch sitemap XML
    $sitemapXml = @file_get_contents($sitemapUrl);
    if ($sitemapXml === false) {
        echo json_encode(['error' => 'Failed to fetch sitemap']);
        exit;
    }

    // Parse URLs from sitemap
    libxml_use_internal_errors(true);
    $xml = @simplexml_load_string($sitemapXml);
    if (!$xml) {
        echo json_encode(['error' => 'Invalid sitemap XML']);
        exit;
    }

    $urls = [];
    foreach ($xml->url as $urlElem) {
        $loc = (string)$urlElem->loc;
        if ($loc && count($urls) < $maxPages) {
            $urls[] = $loc;
        }
    }

    if (empty($urls)) {
        echo json_encode(['error' => 'No URLs found in sitemap']);
        exit;
    }

    $results = [];
    $totalLoadTime = 0;
    $totalSize = 0;
    $totalRequests = 0;
    
    foreach ($urls as $pageUrl) {
        $startTime = microtime(true);
        
        // Initialize cURL
        $ch = curl_init($pageUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'MWPerformanceAudit/1.0',
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => false,
        ]);
        
        $response = curl_exec($ch);
        $loadTime = microtime(true) - $startTime;
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $totalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        $connectTime = curl_getinfo($ch, CURLINFO_CONNECT_TIME);
        $downloadSize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);
        
        if ($response === false || $httpCode >= 400) {
            $results[] = [
                'url' => $pageUrl,
                'status' => 'Error',
                'load_time' => 0,
                'size' => 0,
                'requests' => 0,
                'images' => [],
                'css_files' => [],
                'js_files' => [],
                'issues' => ['Failed to load page'],
                'recommendations' => [],
                'performance_score' => 0
            ];
            continue;
        }
        
        // Extract HTML content
        $html = substr($response, $headerSize);
        $headers = substr($response, 0, $headerSize);
        
        // Parse HTML for resource analysis
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        // Analyze images
        $imgNodes = $xpath->query('//img[@src]');
        $images = [];
        $largeImages = 0;
        $imagesWithoutAlt = 0;
        $imagesWithoutLazyLoading = 0;
        
        foreach ($imgNodes as $img) {
            $src = $img->getAttribute('src');
            $alt = $img->getAttribute('alt');
            $loading = $img->getAttribute('loading');
            $width = $img->getAttribute('width');
            $height = $img->getAttribute('height');
            
            // Convert relative URLs to absolute
            if (strpos($src, 'http') !== 0 && strpos($src, 'data:') !== 0) {
                $parsedUrl = parse_url($pageUrl);
                $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                if (substr($src, 0, 1) === '/') {
                    $src = $baseUrl . $src;
                } else {
                    $src = $baseUrl . '/' . ltrim($src, '/');
                }
            }
            
            // Skip data URLs
            if (strpos($src, 'data:') === 0) continue;
            
            $imageInfo = [
                'src' => $src,
                'has_alt' => !empty($alt),
                'has_lazy_loading' => !empty($loading) && strtolower($loading) === 'lazy',
                'has_dimensions' => !empty($width) && !empty($height)
            ];
            
            // Estimate if image might be large (basic heuristics)
            $pathInfo = pathinfo(parse_url($src, PHP_URL_PATH));
            $extension = isset($pathInfo['extension']) ? strtolower($pathInfo['extension']) : '';
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $images[] = $imageInfo;
                
                if (empty($alt)) $imagesWithoutAlt++;
                if (empty($loading) || strtolower($loading) !== 'lazy') $imagesWithoutLazyLoading++;
                
                // Rough estimation for large images (would need actual size check for accuracy)
                if (empty($width) || empty($height) || $width > 800 || $height > 600) {
                    $largeImages++;
                }
            }
        }
        
        // Analyze CSS files
        $cssNodes = $xpath->query('//link[@rel="stylesheet"][@href] | //style');
        $cssFiles = [];
        $inlineCss = 0;
        
        foreach ($cssNodes as $css) {
            if ($css->tagName === 'style') {
                $inlineCss++;
            } else {
                $href = $css->getAttribute('href');
                if ($href) {
                    $cssFiles[] = $href;
                }
            }
        }
        
        // Analyze JS files
        $jsNodes = $xpath->query('//script[@src] | //script[not(@src)]');
        $jsFiles = [];
        $inlineJs = 0;
        $asyncJs = 0;
        $deferJs = 0;
        
        foreach ($jsNodes as $js) {
            $src = $js->getAttribute('src');
            if ($src) {
                $jsFiles[] = [
                    'src' => $src,
                    'async' => $js->hasAttribute('async'),
                    'defer' => $js->hasAttribute('defer')
                ];
                if ($js->hasAttribute('async')) $asyncJs++;
                if ($js->hasAttribute('defer')) $deferJs++;
            } else {
                $inlineJs++;
            }
        }
        
        // Check for common performance issues
        $issues = [];
        $recommendations = [];
        
        // Image optimization issues
        if ($imagesWithoutAlt > 0) {
            $issues[] = "$imagesWithoutAlt images missing alt text";
            $recommendations[] = "Add alt text to all images for accessibility and SEO";
        }
        
        if ($imagesWithoutLazyLoading > 3) {
            $issues[] = "$imagesWithoutLazyLoading images without lazy loading";
            $recommendations[] = "Implement lazy loading for images below the fold";
        }
        
        if ($largeImages > 0) {
            $issues[] = "$largeImages potentially large images detected";
            $recommendations[] = "Optimize and compress large images, consider WebP format";
        }
        
        // CSS issues
        if (count($cssFiles) > 5) {
            $issues[] = count($cssFiles) . " CSS files found";
            $recommendations[] = "Consider combining CSS files to reduce HTTP requests";
        }
        
        if ($inlineCss > 3) {
            $issues[] = "$inlineCss inline style blocks";
            $recommendations[] = "Move inline CSS to external files for better caching";
        }
        
        // JavaScript issues
        if (count($jsFiles) > 8) {
            $issues[] = count($jsFiles) . " JavaScript files found";
            $recommendations[] = "Consider combining and minifying JavaScript files";
        }
        
        if ($inlineJs > 5) {
            $issues[] = "$inlineJs inline script blocks";
            $recommendations[] = "Move inline JavaScript to external files";
        }
        
        if (($asyncJs + $deferJs) < count($jsFiles) * 0.5) {
            $issues[] = "Many JavaScript files without async/defer";
            $recommendations[] = "Add async or defer attributes to non-critical JavaScript";
        }
        
        // Performance timing issues
        if ($loadTime > 3.0) {
            $issues[] = "Slow page load time (" . number_format($loadTime, 2) . "s)";
            $recommendations[] = "Optimize server response time and reduce resource sizes";
        }
        
        if ($connectTime > 1.0) {
            $issues[] = "Slow connection time (" . number_format($connectTime, 2) . "s)";
            $recommendations[] = "Consider using a CDN or optimizing server configuration";
        }
        
        // Check for common BREN7 optimizations
        $hasViewport = $xpath->query('//meta[@name="viewport"]')->length > 0;
        if (!$hasViewport) {
            $issues[] = "Missing viewport meta tag";
            $recommendations[] = "Add viewport meta tag for mobile optimization";
        }
        
        // Check for compression hints in headers
        $hasGzip = stripos($headers, 'content-encoding: gzip') !== false;
        if (!$hasGzip && $downloadSize > 10000) {
            $issues[] = "No GZIP compression detected";
            $recommendations[] = "Enable GZIP compression on your server";
        }
        
        // Calculate performance score (0-100)
        $score = 100;
        $score -= min(30, $loadTime * 10); // Deduct based on load time
        $score -= min(20, count($issues) * 3); // Deduct for issues
        $score -= min(15, (count($cssFiles) + count($jsFiles)) * 0.5); // Deduct for too many resources
        $score -= min(10, $imagesWithoutLazyLoading * 2); // Deduct for non-lazy images
        $score = max(0, round($score));
        
        $totalLoadTime += $loadTime;
        $totalSize += $downloadSize;
        $totalRequests += (count($cssFiles) + count($jsFiles) + count($images));
        
        $results[] = [
            'url' => $pageUrl,
            'status' => 'Success',
            'load_time' => round($loadTime, 3),
            'connect_time' => round($connectTime, 3),
            'total_time' => round($totalTime, 3),
            'size' => $downloadSize,
            'size_formatted' => formatBytes($downloadSize),
            'requests' => count($cssFiles) + count($jsFiles) + count($images),
            'images' => [
                'total' => count($images),
                'without_alt' => $imagesWithoutAlt,
                'without_lazy' => $imagesWithoutLazyLoading,
                'large_images' => $largeImages
            ],
            'css_files' => count($cssFiles),
            'js_files' => count($jsFiles),
            'inline_css' => $inlineCss,
            'inline_js' => $inlineJs,
            'async_js' => $asyncJs,
            'defer_js' => $deferJs,
            'has_viewport' => $hasViewport,
            'has_gzip' => $hasGzip,
            'issues' => $issues,
            'recommendations' => $recommendations,
            'performance_score' => $score
        ];
    }
    
    // Calculate summary statistics
    $avgLoadTime = count($results) > 0 ? $totalLoadTime / count($results) : 0;
    $avgSize = count($results) > 0 ? $totalSize / count($results) : 0;
    $avgRequests = count($results) > 0 ? $totalRequests / count($results) : 0;
    $avgScore = count($results) > 0 ? array_sum(array_column($results, 'performance_score')) / count($results) : 0;
    
    $slowPages = array_filter($results, function($r) { return $r['load_time'] > 3.0; });
    $fastPages = array_filter($results, function($r) { return $r['load_time'] <= 2.0; });
    $totalIssues = array_sum(array_map(function($r) { return count($r['issues']); }, $results));
    
    echo json_encode([
        'results' => $results,
        'summary' => [
            'total_pages' => count($results),
            'avg_load_time' => round($avgLoadTime, 3),
            'avg_size' => round($avgSize),
            'avg_size_formatted' => formatBytes($avgSize),
            'avg_requests' => round($avgRequests, 1),
            'avg_score' => round($avgScore, 1),
            'slow_pages' => count($slowPages),
            'fast_pages' => count($fastPages),
            'total_issues' => $totalIssues
        ]
    ]);
    exit;
}

function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB');
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . ' ' . $units[$i];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MW Performance Auditor – Sitemap Speed Scores</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Capture Lighthouse performance metrics and core vitals for each sitemap URL with the streamlined MW Performance Auditor from BREN7.">
  <meta name="keywords" content="lighthouse performance auditor, sitemap speed analysis, web vitals checker, performance dashboard, BREN7 tools">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="MW Performance Auditor – Sitemap Speed Scores">
  <meta property="og:description" content="Compare page speed metrics across sitemap URLs with the MW Performance Auditor by BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/sitemap-performance-audit.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="MW Performance Auditor – Sitemap Speed Scores">
  <meta name="twitter:description" content="Review Lighthouse performance scores across your sitemap with this BREN7 tool.">
  <meta name="twitter:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Favicon -->
  <link rel="icon" href="https://bren7.com/images/favicon.jpg" type="image/jpeg">

  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1RGGXKCNB6"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-1RGGXKCNB6');
  </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
      :root {
    /* Core palette */
    --primary-blue: #2563EB;
    --primary-blue-dark: #1D4ED8;
    --secondary-blue: #3B82F6;
    --accent-green: #10B981;
    --accent-orange: #F59E0B;
    --error-red: #EF4444;
    --success-green: #22C55E;

    /* Neutrals */
    --white: #FFFFFF;
    --light-gray: #F8FAFC;
    --medium-gray: #E2E8F0;
    --dark-gray: #374151;
    --neutral-gray: #6B7280;

    /* BREN7 nav overrides */
    --nav-header-bg: #2EB7A0;
    --nav-footer-bg: #DEDEDE;
    --nav-text-default: #333333;
    --nav-text-hover: #000000;

    /* Background accent */
    --light-blue: #E0F2FE;

    /* Hover & fade states */
    --btn-orange-hover: #D97706;
    --fade-primary-blue: rgba(37, 99, 235, 0.04);
    --fade-secondary-blue: rgba(59, 130, 246, 0.1);
    --fade-accent-green: rgba(16, 185, 129, 0.1);
    --fade-error-red: rgba(239, 68, 68, 0.1);
    --fade-success-green: rgba(34, 197, 94, 0.1);
    --fade-light-gray: rgba(248, 250, 252, 0.5);
}

/* Reset & Global */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background: linear-gradient(135deg, var(--light-gray) 0%, var(--light-blue) 100%);
    min-height: 100vh;
    color: var(--dark-gray);
    line-height: 1.6;
}

/* Navigation */
#mwNavigation {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 55px;
    padding: 0 100px;
    background: var(--white);
    z-index: 2000;
}
#mwNavigation .navBarHeader {
    background: var(--nav-header-bg);
    z-index: 2002;
}
#mwNavigation .navBarFooter {
    background: var(--nav-footer-bg);
    z-index: 2001;
}
.navContainer .subNav a {
    color: var(--nav-text-default);
}
.navContainer .topNav:hover .subNav > li a {
    color: var(--nav-text-hover);
}
.navContainer .subNav > li a:hover {
    color: var(--nav-header-bg);
}

/* Layout */
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}
.header {
    text-align: center;
    margin-bottom: 3rem;
    background: var(--white);
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--medium-gray);
}
.header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.header .subtitle {
    font-size: 1.1rem;
    color: var(--neutral-gray);
}

/* Cards */
.card {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--medium-gray);
    transition: all 0.3s ease;
}
.card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}
.card h2 {
    color: var(--primary-blue);
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Forms */
.form-section {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: var(--white);
    border: none;
}
.form-section h2 {
    color: var(--white);
}
.form-group {
    margin-bottom: 1.5rem;
}
.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
}
.input-wrapper {
    position: relative;
    display: flex;
    gap: 0.75rem;
    align-items: stretch;
    margin-bottom: 1rem;
}
input[type="url"], input[type="number"], select {
    flex: 1;
    padding: 0.875rem 1rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.1);
    color: var(--white);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}
input[type="url"]:focus, input[type="number"]:focus, select:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}
input[type="url"]::placeholder, input[type="number"]::placeholder {
    color: rgba(255, 255, 255, 0.6);
}
select option {
    background: var(--dark-gray);
    color: var(--white);
}

.form-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1rem;
    align-items: end;
}

/* Buttons */
.btn {
    padding: 0.875rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}
.btn-primary {
    background: var(--accent-orange);
    color: var(--white);
}
.btn-primary:hover {
    background: var(--btn-orange-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}
.btn-primary:disabled {
    background: var(--neutral-gray);
    cursor: not-allowed;
    transform: none;
}

/* Status Messages */
.status {
    margin: 1.5rem 0;
    padding: 1rem;
    border-radius: 8px;
    font-weight: 500;
    display: none;
}
.status.error {
    background: var(--fade-error-red);
    color: var(--error-red);
    border: 1px solid rgba(239, 68, 68, 0.2);
}
.status.loading {
    background: var(--fade-secondary-blue);
    color: var(--secondary-blue);
    border: 1px solid rgba(59, 130, 246, 0.2);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.status.success {
    background: var(--fade-success-green);
    color: var(--success-green);
    border: 1px solid rgba(34, 197, 94, 0.2);
}

/* Spinner */
.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Tables */
.table-wrapper {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid var(--medium-gray);
    background: var(--white);
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}
th {
    background: linear-gradient(135deg, var(--light-gray), var(--medium-gray));
    color: var(--dark-gray);
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 2px solid var(--medium-gray);
    position: sticky;
    top: 0;
    z-index: 10;
}
td {
    padding: 0.875rem 1rem;
    border-bottom: 1px solid var(--medium-gray);
}
tbody tr:hover {
    background: var(--fade-primary-blue);
}
tbody tr:nth-child(even) {
    background: var(--fade-light-gray);
}

/* Links & Badges */
.page-link {
    color: var(--primary-blue);
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.page-link:hover {
    color: var(--primary-blue-dark);
    text-decoration: underline;
}

.performance-score {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
    min-width: 3rem;
    color: var(--white);
}
.score-excellent { background: var(--success-green); }
.score-good { background: var(--accent-green); }
.score-needs-improvement { background: var(--accent-orange); }
.score-poor { background: var(--error-red); }

.load-time {
    padding: 0.25rem 0.5rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    text-align: center;
    min-width: 4rem;
}
.time-fast { background: var(--fade-success-green); color: var(--success-green); }
.time-medium { background: var(--fade-accent-green); color: var(--accent-green); }
.time-slow { background: var(--fade-error-red); color: var(--error-red); }

.resource-count {
    background: var(--fade-primary-blue);
    color: var(--primary-blue);
    padding: 0.2rem 0.4rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    min-width: 2rem;
}

/* Summary Section */
.summary-section {
    background: linear-gradient(135deg, var(--accent-green), #059669);
    color: var(--white);
    border: none;
}
.summary-section h2 {
    color: var(--white);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.stat-card {
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    backdrop-filter: blur(10px);
}
.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--white);
    display: block;
}
.stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
    margin-top: 0.25rem;
}

/* Issues and Recommendations */
.issues-cell, .recommendations-cell {
    max-width: 300px;
}
.issue-item, .recommendation-item {
    background: var(--fade-error-red);
    color: var(--error-red);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.75rem;
    margin: 0.1rem;
    display: inline-block;
}
.recommendation-item {
    background: var(--fade-secondary-blue);
    color: var(--secondary-blue);
}

/* Instructions */
.instructions ol {
    padding-left: 1.5rem;
}
.instructions li {
    margin-bottom: 0.5rem;
    color: var(--neutral-gray);
}
.instructions code {
    background: var(--light-gray);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    color: var(--primary-blue);
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    .header h1 {
        font-size: 2rem;
    }
    .form-row {
        grid-template-columns: 1fr;
    }
    .input-wrapper {
        flex-direction: column;
    }
    .btn {
        justify-content: center;
    }
    .page-link {
        max-width: 200px;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-tachometer-alt"></i> MW Performance Auditor</h1>
            <p class="subtitle">Analyze page speed, Core Web Vitals, and optimization opportunities across your sitemap</p>
        </div>

        <div class="card">
            <h2><i class="fas fa-info-circle"></i> About This Tool</h2>
            <p>The Performance Auditor analyzes your site's page speed metrics, resource loading, and identifies optimization opportunities. It checks load times, file sizes, resource counts, image optimization, CSS/JavaScript usage, and provides actionable recommendations to improve your BREN7 site's performance.</p>
        </div>

        <div class="card instructions">
            <h2><i class="fas fa-list-ol"></i> How It Works</h2>
            <ol>
                <li>Enter your site's complete sitemap URL (e.g., <code>https://example.com/sitemap.xml</code>)</li>
                <li>Choose how many pages to analyze (1-50 pages, default: 10)</li>
                <li>Click <strong>Start Performance Audit</strong> to begin analysis</li>
                <li>Review detailed performance metrics, scores, and optimization recommendations</li>
                <li>Focus on pages with low performance scores and high issue counts</li>
            </ol>
        </div>

        <div class="card form-section">
            <h2><i class="fas fa-cog"></i> Audit Configuration</h2>
            <form id="audit-form">
                <div class="form-group">
                    <label for="sitemap-url">Sitemap URL</label>
                    <div class="form-row">
                        <input type="url" id="sitemap-url" name="sitemap" placeholder="https://example.com/sitemap.xml" required>
                        <div>
                            <label for="max-pages" style="margin-bottom: 0.25rem;">Pages to Audit</label>
                            <select id="max-pages" name="max_pages">
                                <option value="5">5 pages</option>
                                <option value="10" selected>10 pages</option>
                                <option value="20">20 pages</option>
                                <option value="30">30 pages</option>
                                <option value="50">50 pages</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="audit-btn">
                    <i class="fas fa-play"></i>
                    Start Performance Audit
                </button>
            </form>
        </div>

        <div id="status" class="status"></div>

        <div class="results-container" id="results-container">
            <div class="card summary-section" id="summary-section">
                <h2><i class="fas fa-chart-bar"></i> Performance Summary</h2>
                <div class="stats-grid" id="stats-grid"></div>
            </div>

            <div class="card">
                <h2><i class="fas fa-table"></i> Detailed Performance Results</h2>
                <div class="table-wrapper">
                    <table id="results-table">
                        <thead>
                            <tr>
                                <th>Page URL</th>
                                <th>Performance Score</th>
                                <th>Load Time</th>
                                <th>Page Size</th>
                                <th>Resources</th>
                                <th>Images</th>
                                <th>CSS/JS Files</th>
                                <th>Issues Found</th>
                                <th>Recommendations</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    $('#audit-form').on('submit', function(e) {
        e.preventDefault();
        var sitemapUrl = $('#sitemap-url').val().trim();
        var maxPages = $('#max-pages').val();
        
        if (!sitemapUrl) return;

        // Show loading state
        $('#status').removeClass('error success').addClass('loading').html('<div class="spinner"></div> Analyzing performance across ' + maxPages + ' pages… this may take several minutes...').show();
        $('#audit-btn').prop('disabled', true).html('<div class="spinner"></div> Analyzing...');
        $('#results-container').hide();

        $.post('<?php echo $_SERVER["PHP_SELF"]; ?>', { 
            sitemap: sitemapUrl,
            max_pages: maxPages
        })
         .done(function(data) {
            $('#audit-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Start Performance Audit');
            
            if (data.error) {
                $('#status').removeClass('loading success').addClass('error').text(data.error);
                return;
            }

            var results = data.results || [];
            var summary = data.summary || {};
            
            if (!results.length) {
                $('#status').removeClass('loading error').addClass('success').text('Audit completed successfully, but no pages were analyzed.');
                return;
            }

            $('#status').removeClass('loading error').addClass('success').text(
                'Performance audit completed! Analyzed ' + summary.total_pages + ' pages with an average score of ' + summary.avg_score + '/100.'
            );

            // Populate summary stats
            var $statsGrid = $('#stats-grid');
            $statsGrid.html(
                '<div class="stat-card"><span class="stat-number">' + summary.total_pages + '</span><div class="stat-label">Pages Analyzed</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.avg_score + '</span><div class="stat-label">Average Score</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.avg_load_time + 's</span><div class="stat-label">Average Load Time</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.avg_size_formatted + '</span><div class="stat-label">Average Page Size</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.fast_pages + '</span><div class="stat-label">Fast Pages (≤2s)</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.slow_pages + '</span><div class="stat-label">Slow Pages (>3s)</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.total_issues + '</span><div class="stat-label">Total Issues Found</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.avg_requests + '</span><div class="stat-label">Avg Resources/Page</div></div>'
            );

            // Populate detailed results table
            var $tbody = $('#results-table tbody');
            $tbody.empty();
            
            results.forEach(function(result) {
                if (result.status === 'Error') {
                    $tbody.append(
                        $('<tr>').append(
                            $('<td>').append($('<a>').addClass('page-link').attr('href', result.url).attr('target', '_blank').html('<i class="fas fa-external-link-alt" style="font-size: 0.8em; opacity: 0.7;"></i> ' + (result.url.length > 40 ? result.url.substring(0, 40) + '...' : result.url))),
                            $('<td>').html('<span class="performance-score score-poor">Error</span>'),
                            $('<td>').text('--'),
                            $('<td>').text('--'),
                            $('<td>').text('--'),
                            $('<td>').text('--'),
                            $('<td>').text('--'),
                            $('<td>').html('<span class="issue-item">Failed to load</span>'),
                            $('<td>').text('Check URL accessibility')
                        )
                    );
                    return;
                }

                // Performance score styling
                var scoreClass = 'score-poor';
                if (result.performance_score >= 90) scoreClass = 'score-excellent';
                else if (result.performance_score >= 75) scoreClass = 'score-good';
                else if (result.performance_score >= 50) scoreClass = 'score-needs-improvement';

                // Load time styling
                var timeClass = 'time-slow';
                if (result.load_time <= 2.0) timeClass = 'time-fast';
                else if (result.load_time <= 3.0) timeClass = 'time-medium';

                // Format issues
                var issuesHtml = '';
                if (result.issues && result.issues.length > 0) {
                    issuesHtml = result.issues.slice(0, 3).map(function(issue) {
                        return '<span class="issue-item">' + issue + '</span>';
                    }).join(' ');
                    if (result.issues.length > 3) {
                        issuesHtml += '<span class="issue-item">+' + (result.issues.length - 3) + ' more</span>';
                    }
                }

                // Format recommendations
                var recommendationsHtml = '';
                if (result.recommendations && result.recommendations.length > 0) {
                    recommendationsHtml = result.recommendations.slice(0, 2).map(function(rec) {
                        return '<span class="recommendation-item">' + (rec.length > 50 ? rec.substring(0, 50) + '...' : rec) + '</span>';
                    }).join(' ');
                    if (result.recommendations.length > 2) {
                        recommendationsHtml += '<span class="recommendation-item">+' + (result.recommendations.length - 2) + ' more</span>';
                    }
                }

                $tbody.append(
                    $('<tr>').append(
                        $('<td>').append($('<a>').addClass('page-link').attr('href', result.url).attr('target', '_blank').html('<i class="fas fa-external-link-alt" style="font-size: 0.8em; opacity: 0.7;"></i> ' + (result.url.length > 40 ? result.url.substring(0, 40) + '...' : result.url))),
                        $('<td>').html('<span class="performance-score ' + scoreClass + '">' + result.performance_score + '</span>'),
                        $('<td>').html('<span class="load-time ' + timeClass + '">' + result.load_time + 's</span>'),
                        $('<td>').text(result.size_formatted),
                        $('<td>').html('<span class="resource-count">' + result.requests + '</span>'),
                        $('<td>').html(
                            '<div style="font-size: 0.8rem;">' +
                            '<div>Total: <strong>' + result.images.total + '</strong></div>' +
                            (result.images.without_alt > 0 ? '<div style="color: var(--error-red);">No Alt: ' + result.images.without_alt + '</div>' : '') +
                            (result.images.without_lazy > 0 ? '<div style="color: var(--accent-orange);">No Lazy: ' + result.images.without_lazy + '</div>' : '') +
                            '</div>'
                        ),
                        $('<td>').html(
                            '<div style="font-size: 0.8rem;">' +
                            '<div>CSS: <strong>' + result.css_files + '</strong>' + (result.inline_css > 0 ? ' (+' + result.inline_css + ' inline)' : '') + '</div>' +
                            '<div>JS: <strong>' + result.js_files + '</strong>' + (result.inline_js > 0 ? ' (+' + result.inline_js + ' inline)' : '') + '</div>' +
                            '</div>'
                        ),
                        $('<td>').addClass('issues-cell').html(issuesHtml || '<span style="color: var(--success-green); font-size: 0.8rem;">No issues</span>'),
                        $('<td>').addClass('recommendations-cell').html(recommendationsHtml || '<span style="color: var(--success-green); font-size: 0.8rem;">Well optimized</span>')
                    )
                );
            });

            $('#results-container').show();
        })
         .fail(function(xhr, status, error) {
            $('#audit-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Start Performance Audit');
            $('#status').removeClass('loading success').addClass('error').text('Request failed: ' + error);
        });
    });
    </script>
</body>
</html>