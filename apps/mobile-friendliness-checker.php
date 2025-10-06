<?php
// Mobile Friendliness Checker
// Input a sitemap URL, fetch all pages, analyze mobile-friendliness issues,
// and display detailed results with summary statistics.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    // Validate sitemap URL
    if (empty($_POST['sitemap']) || !filter_var($_POST['sitemap'], FILTER_VALIDATE_URL)) {
        echo json_encode(['error' => 'Invalid or missing sitemap URL']);
        exit;
    }
    $sitemapUrl = $_POST['sitemap'];
    $maxPages = isset($_POST['maxPages']) ? (int)$_POST['maxPages'] : 10;
    $timeout = isset($_POST['timeout']) ? (int)$_POST['timeout'] : 30;

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
        if ($loc) {
            $urls[] = $loc;
        }
    }

    if (empty($urls)) {
        echo json_encode(['error' => 'No URLs found in sitemap']);
        exit;
    }

    // Limit pages to scan
    $urls = array_slice($urls, 0, $maxPages);

    // Scan each URL for mobile issues
    $results = [];
    $totalPages = count($urls);
    $processedPages = 0;
    $issueSummary = [];
    $scoreSummary = [];
    
    foreach ($urls as $pageUrl) {
        $startTime = microtime(true);
        
        $ch = curl_init($pageUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'MobileFriendlinessChecker/1.0',
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_MAXREDIRS      => 3
        ]);
        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $loadTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        $processedPages++;
        
        if ($html === false || $httpCode >= 400) {
            $results[] = [
                'page' => $pageUrl,
                'score' => 0,
                'issues' => ['Failed to fetch page (HTTP ' . $httpCode . ')'],
                'loadTime' => 0,
                'status' => 'Error',
                'critical_issues' => 1,
                'warning_issues' => 0,
                'info_issues' => 0
            ];
            continue;
        }

        // Analyze page for mobile-friendliness with responsive design testing
        $issues = [];
        $criticalIssues = 0;
        $warningIssues = 0;
        $infoIssues = 0;
        $responsiveResults = analyzeResponsiveDesign($html, $pageUrl);
        
        // Add responsive design issues to main issues list
        if (!$responsiveResults['has_media_queries']) {
            $issues[] = 'No media queries detected - may not be responsive';
            $warningIssues++;
            $issueSummary['responsive-design'] = ($issueSummary['responsive-design'] ?? 0) + 1;
        }
        
        if (!$responsiveResults['responsive_images']) {
            $issues[] = 'Images may not be responsive (missing srcset/picture elements)';
            $warningIssues++;
            $issueSummary['responsive-images'] = ($issueSummary['responsive-images'] ?? 0) + 1;
        }
        
        if (!$responsiveResults['flex_grid_usage']) {
            $issues[] = 'No modern layout methods detected (flexbox/grid)';
            $infoIssues++;
            $issueSummary['modern-layout'] = ($issueSummary['modern-layout'] ?? 0) + 1;
        }
        
        if (count($responsiveResults['breakpoints']) < 2) {
            $issues[] = 'Limited responsive breakpoints (' . count($responsiveResults['breakpoints']) . ' detected)';
            $infoIssues++;
            $issueSummary['breakpoints'] = ($issueSummary['breakpoints'] ?? 0) + 1;
        }
        
        // Check device compatibility scores
        foreach ($responsiveResults['device_compatibility'] as $device => $compatibility) {
            if ($compatibility['score'] < 70) {
                $issues[] = 'Poor compatibility with ' . $device . ' (' . $compatibility['score'] . '/100)';
                if ($compatibility['score'] < 50) {
                    $criticalIssues++;
                } else {
                    $warningIssues++;
                }
                $issueSummary['device-compatibility'] = ($issueSummary['device-compatibility'] ?? 0) + 1;
            }
        }
        
        // Check viewport meta tag
        if (!preg_match('/<meta[^>]*name=["\']viewport["\'][^>]*>/i', $html)) {
            $issues[] = 'Missing viewport meta tag';
            $criticalIssues++;
            $issueSummary['viewport'] = ($issueSummary['viewport'] ?? 0) + 1;
        }
        
        // Check for small font sizes
        if (preg_match_all('/font-size:\s*(\d+(?:\.\d+)?)px/i', $html, $matches)) {
            $smallFonts = [];
            foreach ($matches[1] as $size) {
                if ((float)$size < 12) {
                    $smallFonts[] = $size . 'px';
                }
            }
            if (!empty($smallFonts)) {
                $issues[] = 'Small font sizes detected: ' . implode(', ', array_unique($smallFonts));
                $warningIssues++;
                $issueSummary['font-size'] = ($issueSummary['font-size'] ?? 0) + 1;
            }
        }
        
        // Check for responsive images
        if (preg_match('/<img[^>]*>/i', $html)) {
            if (!preg_match('/max-width:\s*100%|width:\s*100%|responsive/i', $html)) {
                $issues[] = 'Images may not be responsive';
                $warningIssues++;
                $issueSummary['images'] = ($issueSummary['images'] ?? 0) + 1;
            }
            
            // Check for srcset
            if (!preg_match('/<img[^>]*srcset/i', $html)) {
                $issues[] = 'Images missing srcset for responsive design';
                $infoIssues++;
                $issueSummary['srcset'] = ($issueSummary['srcset'] ?? 0) + 1;
            }
        }
        
        // Check for touch target sizes
        if (preg_match_all('/(?:width|height):\s*(\d+)px/i', $html, $matches)) {
            $smallElements = [];
            foreach ($matches[1] as $size) {
                if ((int)$size < 44 && (int)$size > 0) {
                    $smallElements[] = $size . 'px';
                }
            }
            if (!empty($smallElements)) {
                $issues[] = 'Small touch targets detected: ' . implode(', ', array_slice(array_unique($smallElements), 0, 3));
                $warningIssues++;
                $issueSummary['touch-targets'] = ($issueSummary['touch-targets'] ?? 0) + 1;
            }
        }
        
        // Check for deprecated tags
        $deprecatedTags = ['<font', '<center', '<marquee', '<blink'];
        $foundDeprecated = [];
        foreach ($deprecatedTags as $tag) {
            if (stripos($html, $tag) !== false) {
                $foundDeprecated[] = trim($tag, '<');
            }
        }
        if (!empty($foundDeprecated)) {
            $issues[] = 'Deprecated HTML tags found: ' . implode(', ', $foundDeprecated);
            $infoIssues++;
            $issueSummary['deprecated'] = ($issueSummary['deprecated'] ?? 0) + 1;
        }
        
        // Check for fixed width elements
        if (preg_match('/width:\s*\d{4,}px/i', $html)) {
            $issues[] = 'Fixed width elements may cause horizontal scrolling';
            $criticalIssues++;
            $issueSummary['horizontal-scroll'] = ($issueSummary['horizontal-scroll'] ?? 0) + 1;
        }
        
        // Check page load performance
        $performanceScore = 100;
        if ($loadTime > 3000) {
            $issues[] = 'Slow page load time (' . round($loadTime/1000, 2) . 's)';
            $warningIssues++;
            $issueSummary['performance'] = ($issueSummary['performance'] ?? 0) + 1;
            $performanceScore -= 20;
        } elseif ($loadTime > 2000) {
            $performanceScore -= 10;
        }
        
        // Calculate mobile score
        $score = 100;
        $score -= $criticalIssues * 25;  // Critical issues: -25 points each
        $score -= $warningIssues * 10;   // Warning issues: -10 points each
        $score -= $infoIssues * 5;       // Info issues: -5 points each
        $score = max(0, min(100, $score));
        
        // Categorize score
        if ($score >= 81) $scoreSummary['good'] = ($scoreSummary['good'] ?? 0) + 1;
        elseif ($score >= 51) $scoreSummary['warning'] = ($scoreSummary['warning'] ?? 0) + 1;
        else $scoreSummary['poor'] = ($scoreSummary['poor'] ?? 0) + 1;
        
        $results[] = [
            'page' => $pageUrl,
            'score' => $score,
            'issues' => $issues,
            'loadTime' => round($loadTime),
            'status' => empty($issues) ? 'Perfect' : count($issues) . ' issue' . (count($issues) > 1 ? 's' : ''),
            'critical_issues' => $criticalIssues,
            'warning_issues' => $warningIssues,
            'info_issues' => $infoIssues,
            'responsive_data' => $responsiveResults
        ];
    }

    // Calculate summary statistics
    $totalIssues = array_sum(array_column($results, 'critical_issues')) + 
                   array_sum(array_column($results, 'warning_issues')) + 
                   array_sum(array_column($results, 'info_issues'));
    $avgScore = $processedPages > 0 ? round(array_sum(array_column($results, 'score')) / $processedPages) : 0;
    $totalCritical = array_sum(array_column($results, 'critical_issues'));
    $totalWarning = array_sum(array_column($results, 'warning_issues'));
    $totalInfo = array_sum(array_column($results, 'info_issues'));

    // Return JSON response
    echo json_encode([
        'results' => $results,
        'issueSummary' => $issueSummary,
        'scoreSummary' => $scoreSummary,
        'totalPages' => $processedPages,
        'totalIssues' => $totalIssues,
        'totalCritical' => $totalCritical,
        'totalWarning' => $totalWarning,
        'totalInfo' => $totalInfo,
        'avgScore' => $avgScore,
        'avgLoadTime' => $processedPages > 0 ? round(array_sum(array_column($results, 'loadTime')) / $processedPages) : 0
    ]);
    exit;
}

function analyzeResponsiveDesign($html, $pageUrl) {
    $responsive = [
        'has_media_queries' => false,
        'breakpoints' => [],
        'viewport_meta' => null,
        'responsive_images' => false,
        'flex_grid_usage' => false,
        'touch_friendly' => false,
        'device_compatibility' => [],
        'orientation_support' => false
    ];
    
    // Extract and analyze CSS
    $cssContent = '';
    
    // Extract inline CSS
    if (preg_match_all('/<style[^>]*>(.*?)<\/style>/is', $html, $styleMatches)) {
        foreach ($styleMatches[1] as $style) {
            $cssContent .= $style . "\n";
        }
    }
    
    // Extract external CSS (for basic analysis)
    if (preg_match_all('/<link[^>]*rel=["\']stylesheet["\'][^>]*href=["\']([^"\']+)["\'][^>]*>/i', $html, $linkMatches)) {
        // Note: In production, you'd want to fetch and analyze external CSS files
        $responsive['external_css_count'] = count($linkMatches[1]);
    }
    
    // Check for media queries
    if (preg_match_all('/@media[^{]*\([^)]*\)[^{]*\{/i', $cssContent, $mediaMatches)) {
        $responsive['has_media_queries'] = true;
        
        // Extract breakpoints
        foreach ($mediaMatches[0] as $mediaQuery) {
            if (preg_match('/(\d+)px/', $mediaQuery, $breakpointMatch)) {
                $responsive['breakpoints'][] = (int)$breakpointMatch[1];
            }
        }
        $responsive['breakpoints'] = array_unique($responsive['breakpoints']);
        sort($responsive['breakpoints']);
    }
    
    // Analyze viewport meta tag
    if (preg_match('/<meta[^>]*name=["\']viewport["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i', $html, $viewportMatch)) {
        $responsive['viewport_meta'] = $viewportMatch[1];
        
        // Check for common responsive viewport settings
        $content = strtolower($viewportMatch[1]);
        $responsive['viewport_responsive'] = (
            strpos($content, 'width=device-width') !== false ||
            strpos($content, 'initial-scale=1') !== false
        );
    }
    
    // Check for responsive images
    $responsive['responsive_images'] = (
        preg_match('/srcset\s*=/i', $html) ||
        preg_match('/sizes\s*=/i', $html) ||
        preg_match('/<picture>/i', $html) ||
        preg_match('/max-width:\s*100%/i', $cssContent)
    );
    
    // Check for modern layout methods
    $responsive['flex_grid_usage'] = (
        preg_match('/display:\s*flex/i', $cssContent) ||
        preg_match('/display:\s*grid/i', $cssContent) ||
        preg_match('/flex-direction/i', $cssContent) ||
        preg_match('/grid-template/i', $cssContent)
    );
    
    // Check for touch-friendly elements
    $responsive['touch_friendly'] = (
        preg_match('/touch-action/i', $cssContent) ||
        preg_match('/-webkit-touch-callout/i', $cssContent) ||
        preg_match('/cursor:\s*pointer/i', $cssContent)
    );
    
    // Check for orientation support
    $responsive['orientation_support'] = preg_match('/@media[^{]*orientation/i', $cssContent);
    
    // Simulate device compatibility testing
    $commonDevices = [
        'iPhone SE' => ['width' => 375, 'height' => 667],
        'iPhone 12' => ['width' => 390, 'height' => 844],
        'Samsung Galaxy S21' => ['width' => 360, 'height' => 800],
        'iPad' => ['width' => 768, 'height' => 1024],
        'Desktop' => ['width' => 1920, 'height' => 1080]
    ];
    
    foreach ($commonDevices as $device => $dimensions) {
        $compatibility = analyzeDeviceCompatibility($html, $cssContent, $dimensions);
        $responsive['device_compatibility'][$device] = $compatibility;
    }
    
    return $responsive;
}

function analyzeDeviceCompatibility($html, $css, $dimensions) {
    $compatibility = [
        'score' => 100,
        'issues' => [],
        'width' => $dimensions['width'],
        'height' => $dimensions['height']
    ];
    
    // Check if viewport width would cause horizontal scrolling
    if (preg_match_all('/width:\s*(\d+)px/i', $css, $widthMatches)) {
        foreach ($widthMatches[1] as $width) {
            if ((int)$width > $dimensions['width']) {
                $compatibility['issues'][] = 'Fixed width (' . $width . 'px) exceeds device width';
                $compatibility['score'] -= 15;
            }
        }
    }
    
    // Check for appropriate font sizes
    if (preg_match_all('/font-size:\s*(\d+(?:\.\d+)?)px/i', $css, $fontMatches)) {
        foreach ($fontMatches[1] as $fontSize) {
            if ((float)$fontSize < 14 && $dimensions['width'] < 768) {
                $compatibility['issues'][] = 'Font size ' . $fontSize . 'px may be too small for mobile';
                $compatibility['score'] -= 10;
            }
        }
    }
    
    // Check for adequate touch targets
    if ($dimensions['width'] < 768) {
        if (preg_match_all('/(?:width|height):\s*(\d+)px/i', $css, $sizeMatches)) {
            foreach ($sizeMatches[1] as $size) {
                if ((int)$size < 44 && (int)$size > 10) {
                    $compatibility['issues'][] = 'Touch target ' . $size . 'px may be too small';
                    $compatibility['score'] -= 5;
                }
            }
        }
    }
    
    $compatibility['score'] = max(0, $compatibility['score']);
    return $compatibility;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Friendliness Checker</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Explore web tools, generators, and utilities from BREN7 to enhance your digital projects.">
  <meta name="keywords" content="BREN7, web tools, generators, accessibility, SEO, performance, utilities">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="BREN7 – Web Projects, Tools & Experiments">
  <meta property="og:description" content="Browse a collection of creative web tools, games, and utilities built by BREN7.">
  <meta property="og:url" content="https://bren7.com/">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="BREN7 – Web Projects, Tools & Experiments">
  <meta name="twitter:description" content="Interactive tools and experiments by BREN7. Explore beat makers, checkers, and more.">
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
}
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
}
input[type="url"], select {
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
input[type="url"]:focus, select:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}
input[type="url"]::placeholder {
    color: rgba(255, 255, 255, 0.6);
}
select option {
    background: var(--primary-blue);
    color: var(--white);
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

.score-badge {
    font-weight: bold;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    text-align: center;
    min-width: 50px;
    font-size: 0.9rem;
}
.score-good {
    background: var(--fade-success-green);
    color: var(--success-green);
    border: 1px solid rgba(34, 197, 94, 0.3);
}
.score-warning {
    background: #FEF3C7;
    color: #D97706;
    border: 1px solid rgba(217, 119, 6, 0.3);
}
.score-poor {
    background: var(--fade-error-red);
    color: var(--error-red);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.issue-badge {
    background: #FEF3C7;
    color: #D97706;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    margin: 0.1rem;
    display: inline-block;
}
.issue-critical {
    background: var(--fade-error-red);
    color: var(--error-red);
}
.issue-warning {
    background: #FEF3C7;
    color: #D97706;
}
.issue-info {
    background: #DBEAFE;
    color: var(--primary-blue);
}

.count-badge {
    background: var(--accent-orange);
    color: var(--white);
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
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
.summary-section h2, .summary-section h3 {
    color: var(--white);
}
.summary-table th {
    background: rgba(229, 231, 235, 0.3);
    color: var(--dark-gray);
    border-bottom-color: var(--medium-gray);
}
.summary-table td {
    color: var(--dark-gray);
    border-bottom-color: var(--medium-gray);
}
.summary-table tbody tr:nth-child(even) {
    background: var(--light-gray);
}
.summary-table tbody tr:hover {
    background: var(--fade-secondary-blue);
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
    .input-wrapper {
        flex-direction: column;
    }
    .settings-grid {
        grid-template-columns: 1fr;
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
            <h1><i class="fas fa-mobile-alt"></i> Mobile Friendliness Checker</h1>
            <p class="subtitle">Analyze your website's mobile-friendliness across all pages with detailed issue detection</p>
        </div>

        <div class="card">
            <h2><i class="fas fa-info-circle"></i> About This Tool</h2>
            <p>This advanced checker analyzes your site's sitemap to identify mobile-friendliness issues and performs comprehensive responsive design testing. It detects missing viewport tags, small fonts, non-responsive images, touch target sizes, deprecated HTML, performance issues, and now includes <strong>advanced responsive design analysis</strong> with multi-device compatibility testing, media query detection, and breakpoint analysis.</p>
        </div>

        <div class="card instructions">
            <h2><i class="fas fa-list-ol"></i> How It Works</h2>
            <ol>
                <li>Enter your site's complete sitemap URL (e.g., <code>https://example.com/sitemap.xml</code>)</li>
                <li>Configure scan settings (number of pages and timeout)</li>
                <li>Click <strong>Start Mobile Scan</strong> to begin analyzing all pages</li>
                <li>Review detailed results showing mobile-friendliness issues for each page</li>
                <li>Analyze responsive design data including media queries and breakpoints</li>
                <li>Check device compatibility scores for iPhone, Android, iPad, and Desktop</li>
                <li>Review summary statistics for overall mobile optimization insights</li>
            </ol>
        </div>

        <div class="card form-section">
            <h2><i class="fas fa-search"></i> Scan Configuration</h2>
            <form id="scan-form">
                <div class="form-group">
                    <label for="sitemap-url">Sitemap URL</label>
                    <div class="input-wrapper">
                        <input type="url" id="sitemap-url" name="sitemap" placeholder="https://example.com/sitemap.xml" required>
                        <button type="submit" class="btn btn-primary" id="scan-btn">
                            <i class="fas fa-play"></i>
                            Start Mobile Scan
                        </button>
                    </div>
                </div>
                <div class="settings-grid">
                    <div class="form-group">
                        <label for="max-pages">Max Pages</label>
                        <select id="max-pages" name="maxPages">
                            <option value="5">5 pages</option>
                            <option value="10" selected>10 pages</option>
                            <option value="25">25 pages</option>
                            <option value="50">50 pages</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="timeout">Timeout</label>
                        <select id="timeout" name="timeout">
                            <option value="15">15 seconds</option>
                            <option value="30" selected>30 seconds</option>
                            <option value="60">60 seconds</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div id="status" class="status"></div>

        <div class="results-container" id="results-container">
            <div class="card">
                <h2><i class="fas fa-table"></i> Detailed Mobile Analysis Results</h2>
                <div class="table-wrapper">
                    <table id="results-table">
                        <thead>
                            <tr>
                                <th>Page URL</th>
                                <th>Mobile Score</th>
                                <th>Issues Found</th>
                                <th>Load Time</th>
                                <th>Responsive</th>
                                <th>Breakpoints</th>
                                <th>Critical</th>
                                <th>Warning</th>
                                <th>Info</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="card summary-section" id="summary-section">
                <h2><i class="fas fa-chart-bar"></i> Mobile Optimization Summary</h2>
                <div class="stats-grid" id="stats-grid"></div>
                
                <h3 style="margin: 2rem 0 1rem 0;"><i class="fas fa-exclamation-triangle"></i> Issue Distribution</h3>
                <div class="table-wrapper">
                    <table id="issue-table" class="summary-table">
                        <thead>
                            <tr>
                                <th>Issue Type</th>
                                <th>Pages Affected</th>
                                <th>Percentage</th>
                                <th>Severity</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                
                <h3 style="margin: 2rem 0 1rem 0;"><i class="fas fa-tachometer-alt"></i> Score Distribution</h3>
                <div class="table-wrapper">
                    <table id="score-table" class="summary-table">
                        <thead>
                            <tr>
                                <th>Score Range</th>
                                <th>Page Count</th>
                                <th>Percentage</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    $('#scan-form').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            sitemap: $('#sitemap-url').val().trim(),
            maxPages: $('#max-pages').val(),
            timeout: $('#timeout').val()
        };
        
        if (!formData.sitemap) return;

        // Show loading state
        $('#status').removeClass('error success').addClass('loading').html('<div class="spinner"></div> Scanning sitemap for mobile issues… this may take a while...').show();
        $('#scan-btn').prop('disabled', true).html('<div class="spinner"></div> Scanning...');
        $('#results-container').hide();

        $.post('<?php echo $_SERVER["PHP_SELF"]; ?>', formData)
         .done(function(data) {
            $('#scan-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Start Mobile Scan');
            
            if (data.error) {
                $('#status').removeClass('loading success').addClass('error').text(data.error);
                return;
            }

            var results = data.results || [];
            var totalPages = data.totalPages || 0;
            var totalIssues = data.totalIssues || 0;
            var avgScore = data.avgScore || 0;
            
            if (!results.length) {
                $('#status').removeClass('loading error').addClass('success').text('Scan completed successfully, but no pages were analyzed.');
                return;
            }

            $('#status').removeClass('loading error').addClass('success').text(
                'Scan completed! Analyzed ' + totalPages + ' pages with ' + totalIssues + ' total issues found. Average mobile score: ' + avgScore + '/100'
            );

            // Populate detailed results
            var $tbody = $('#results-table tbody');
            $tbody.empty();
            
            results.forEach(function(r) {
                var scoreClass = r.score >= 81 ? 'score-good' : (r.score >= 51 ? 'score-warning' : 'score-poor');
                var loadTime = r.loadTime ? (r.loadTime / 1000).toFixed(2) + 's' : 'N/A';
                
                var issuesHtml = '';
                if (r.issues && r.issues.length > 0) {
                    issuesHtml = r.issues.map(function(issue) {
                        var issueClass = 'issue-info';
                        if (issue.includes('viewport') || issue.includes('horizontal') || issue.includes('Failed')) {
                            issueClass = 'issue-critical';
                        } else if (issue.includes('font') || issue.includes('Slow') || issue.includes('touch')) {
                            issueClass = 'issue-warning';
                        }
                        return '<span class="issue-badge ' + issueClass + '">' + issue + '</span>';
                    }).join(' ');
                } else {
                    issuesHtml = '<span style="color: var(--success-green); font-weight: 500;">No issues found</span>';
                }

                $tbody.append(
                    $('<tr>').append(
                        $('<td>').append($('<a>').addClass('page-link').attr('href', r.page).attr('target', '_blank').html('<i class="fas fa-external-link-alt" style="font-size: 0.8em; opacity: 0.7;"></i> ' + (r.page.length > 50 ? r.page.substring(0, 50) + '...' : r.page))),
                        $('<td>').html('<span class="score-badge ' + scoreClass + '">' + r.score + '</span>'),
                        $('<td>').html(issuesHtml),
                        $('<td>').text(loadTime),
                        $('<td>').html(r.critical_issues > 0 ? '<span class="count-badge" style="background: var(--error-red);">' + r.critical_issues + '</span>' : '-'),
                        $('<td>').html(r.warning_issues > 0 ? '<span class="count-badge" style="background: var(--accent-orange);">' + r.warning_issues + '</span>' : '-'),
                        $('<td>').html(r.info_issues > 0 ? '<span class="count-badge" style="background: var(--primary-blue);">' + r.info_issues + '</span>' : '-'),
                        $('<td>').text(r.status)
                    )
                );
            });

            // Create stats cards
            var $statsGrid = $('#stats-grid');
            $statsGrid.html(
                '<div class="stat-card"><span class="stat-number">' + totalPages + '</span><div class="stat-label">Pages Analyzed</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + totalIssues + '</span><div class="stat-label">Total Issues</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + avgScore + '</span><div class="stat-label">Average Score</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + (data.avgLoadTime ? (data.avgLoadTime / 1000).toFixed(1) + 's' : 'N/A') + '</span><div class="stat-label">Avg Load Time</div></div>'
            );

            // Populate issue distribution table
            var issueData = data.issueSummary || {};
            var $issueTbody = $('#issue-table tbody');
            $issueTbody.empty();
            
            var issueTypes = {
                'viewport': { name: 'Missing Viewport Meta', severity: 'Critical' },
                'font-size': { name: 'Small Font Sizes', severity: 'Warning' },
                'images': { name: 'Non-Responsive Images', severity: 'Warning' },
                'srcset': { name: 'Missing Srcset', severity: 'Info' },
                'touch-targets': { name: 'Small Touch Targets', severity: 'Warning' },
                'deprecated': { name: 'Deprecated HTML Tags', severity: 'Info' },
                'horizontal-scroll': { name: 'Horizontal Scrolling', severity: 'Critical' },
                'performance': { name: 'Slow Load Times', severity: 'Warning' },
                'responsive-design': { name: 'No Media Queries', severity: 'Warning' },
                'responsive-images': { name: 'Non-Responsive Images', severity: 'Warning' },
                'modern-layout': { name: 'No Modern Layout Methods', severity: 'Info' },
                'breakpoints': { name: 'Limited Breakpoints', severity: 'Info' },
                'device-compatibility': { name: 'Poor Device Compatibility', severity: 'Warning' }
            };
            
            Object.keys(issueData).forEach(function(issueKey) {
                var count = issueData[issueKey];
                var percentage = ((count / totalPages) * 100).toFixed(1);
                var issueInfo = issueTypes[issueKey] || { name: issueKey, severity: 'Info' };
                var severityClass = issueInfo.severity === 'Critical' ? 'issue-critical' : 
                                  (issueInfo.severity === 'Warning' ? 'issue-warning' : 'issue-info');
                
                $issueTbody.append(
                    $('<tr>').append(
                        $('<td>').text(issueInfo.name),
                        $('<td>').html('<span class="count-badge">' + count + '</span>'),
                        $('<td>').text(percentage + '%'),
                        $('<td>').html('<span class="issue-badge ' + severityClass + '">' + issueInfo.severity + '</span>')
                    )
                );
            });

            // Populate score distribution table
            var scoreData = data.scoreSummary || {};
            var $scoreTbody = $('#score-table tbody');
            $scoreTbody.empty();
            
            var scoreRanges = [
                { key: 'good', range: '81-100', status: 'Excellent', class: 'score-good' },
                { key: 'warning', range: '51-80', status: 'Needs Work', class: 'score-warning' },
                { key: 'poor', range: '0-50', status: 'Poor', class: 'score-poor' }
            ];
            
            scoreRanges.forEach(function(range) {
                var count = scoreData[range.key] || 0;
                var percentage = totalPages > 0 ? ((count / totalPages) * 100).toFixed(1) : '0';
                
                $scoreTbody.append(
                    $('<tr>').append(
                        $('<td>').text(range.range),
                        $('<td>').html('<span class="count-badge">' + count + '</span>'),
                        $('<td>').text(percentage + '%'),
                        $('<td>').html('<span class="score-badge ' + range.class + '">' + range.status + '</span>')
                    )
                );
            });

            $('#results-container').show();
        })
         .fail(function(xhr, status, error) {
            $('#scan-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Start Mobile Scan');
            $('#status').removeClass('loading success').addClass('error').text('Request failed: ' + error);
        });
    });
    </script>
</body>
</html>