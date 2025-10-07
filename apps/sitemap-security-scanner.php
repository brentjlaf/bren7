<?php
// mwsecurityscan.php - Sitemap-based security auditing tool
// Performs basic security checks: HTTP headers, SSL configuration, exposed files, and common vulnerabilities

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
    $maxPages = max(1, min($maxPages, 25)); // Limit between 1-25 pages for security scanning
    
    $checkExposedFiles = isset($_POST['check_exposed_files']) && $_POST['check_exposed_files'] === '1';

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

    // Get base domain for exposed file checks
    $baseDomain = '';
    if (!empty($urls)) {
        $parsedUrl = parse_url($urls[0]);
        $baseDomain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
    }

    $results = [];
    $exposedFiles = [];
    
    // Common exposed files to check
    $commonExposedFiles = [
        '/.htaccess',
        '/wp-config.php',
        '/config.php',
        '/phpinfo.php',
        '/info.php',
        '/test.php',
        '/backup.sql',
        '/database.sql',
        '/admin.php',
        '/login.php',
        '/.env',
        '/composer.json',
        '/package.json',
        '/.git/config',
        '/robots.txt', // Not necessarily exposed, but good to check
        '/sitemap.xml'
    ];

    // Check for exposed files if requested
    if ($checkExposedFiles && $baseDomain) {
        foreach ($commonExposedFiles as $file) {
            $fileUrl = $baseDomain . $file;
            $ch = curl_init($fileUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_USERAGENT => 'MWSecurityScan/1.0',
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HEADER => true,
                CURLOPT_NOBODY => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                $exposedFiles[] = [
                    'file' => $file,
                    'url' => $fileUrl,
                    'status' => 'Accessible',
                    'risk' => getRiskLevel($file)
                ];
            }
        }
    }
    
    foreach ($urls as $pageUrl) {
        $ch = curl_init($pageUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'MWSecurityScan/1.0',
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $sslVerifyResult = curl_getinfo($ch, CURLINFO_SSL_VERIFYRESULT);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($response === false || $httpCode >= 400) {
            $results[] = [
                'url' => $pageUrl,
                'status' => 'Error',
                'ssl_valid' => false,
                'security_headers' => [],
                'vulnerabilities' => ['Failed to access page: ' . ($curlError ?: 'HTTP ' . $httpCode)],
                'recommendations' => [],
                'security_score' => 0
            ];
            continue;
        }
        
        // Extract headers and HTML content
        $headers = substr($response, 0, $headerSize);
        $html = substr($response, $headerSize);
        $headerLines = explode("\n", $headers);
        
        // Parse headers into associative array
        $parsedHeaders = [];
        foreach ($headerLines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $parsedHeaders[strtolower(trim($key))] = trim($value);
            }
        }
        
        // Security header checks
        $securityHeaders = [
            'x-frame-options' => isset($parsedHeaders['x-frame-options']),
            'x-content-type-options' => isset($parsedHeaders['x-content-type-options']),
            'x-xss-protection' => isset($parsedHeaders['x-xss-protection']),
            'strict-transport-security' => isset($parsedHeaders['strict-transport-security']),
            'content-security-policy' => isset($parsedHeaders['content-security-policy']),
            'referrer-policy' => isset($parsedHeaders['referrer-policy']),
            'permissions-policy' => isset($parsedHeaders['permissions-policy']),
        ];
        
        // SSL/TLS validation
        $sslValid = ($sslVerifyResult === 0 && strpos($pageUrl, 'https://') === 0);
        
        // Parse HTML for security issues
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        // Check for common vulnerabilities
        $vulnerabilities = [];
        $recommendations = [];
        
        // Check for mixed content (HTTP resources on HTTPS pages)
        if (strpos($pageUrl, 'https://') === 0) {
            $httpResources = $xpath->query('//img[@src[starts-with(., "http://")]] | //script[@src[starts-with(., "http://")]] | //link[@href[starts-with(., "http://")]]');
            if ($httpResources->length > 0) {
                $vulnerabilities[] = "Mixed content: {$httpResources->length} HTTP resources on HTTPS page";
                $recommendations[] = "Update all resource URLs to use HTTPS";
            }
        }
        
        // Check for forms without HTTPS
        $forms = $xpath->query('//form[@action]');
        foreach ($forms as $form) {
            $action = $form->getAttribute('action');
            if (strpos($action, 'http://') === 0) {
                $vulnerabilities[] = "Insecure form submission to HTTP";
                $recommendations[] = "Ensure all forms submit to HTTPS endpoints";
                break;
            }
        }
        
        // Check for external scripts from non-HTTPS sources
        $scripts = $xpath->query('//script[@src]');
        $insecureScripts = 0;
        foreach ($scripts as $script) {
            $src = $script->getAttribute('src');
            if (strpos($src, 'http://') === 0) {
                $insecureScripts++;
            }
        }
        if ($insecureScripts > 0) {
            $vulnerabilities[] = "$insecureScripts external scripts loaded over HTTP";
            $recommendations[] = "Load all external scripts over HTTPS";
        }
        
        // Check for potential information disclosure
        $comments = $xpath->query('//comment()');
        $sensitiveComments = 0;
        foreach ($comments as $comment) {
            $content = strtolower($comment->textContent);
            if (strpos($content, 'password') !== false || 
                strpos($content, 'debug') !== false || 
                strpos($content, 'todo') !== false ||
                strpos($content, 'fixme') !== false) {
                $sensitiveComments++;
            }
        }
        if ($sensitiveComments > 0) {
            $vulnerabilities[] = "$sensitiveComments potentially sensitive HTML comments";
            $recommendations[] = "Remove or sanitize HTML comments containing sensitive information";
        }
        
        // Check for inline JavaScript (potential XSS risk)
        $inlineScripts = $xpath->query('//script[not(@src)]');
        if ($inlineScripts->length > 5) {
            $vulnerabilities[] = "Many inline scripts (" . $inlineScripts->length . ") - potential XSS risk";
            $recommendations[] = "Move inline JavaScript to external files and implement CSP";
        }
        
        // Check for autocomplete on password fields
        $passwordFields = $xpath->query('//input[@type="password"][@autocomplete="on" or not(@autocomplete)]');
        if ($passwordFields->length > 0) {
            $vulnerabilities[] = "Password fields without autocomplete=off";
            $recommendations[] = "Set autocomplete='off' on sensitive form fields";
        }
        
        // Security header recommendations
        if (!$securityHeaders['x-frame-options']) {
            $vulnerabilities[] = "Missing X-Frame-Options header";
            $recommendations[] = "Add X-Frame-Options: DENY or SAMEORIGIN header";
        }
        
        if (!$securityHeaders['x-content-type-options']) {
            $vulnerabilities[] = "Missing X-Content-Type-Options header";
            $recommendations[] = "Add X-Content-Type-Options: nosniff header";
        }
        
        if (!$securityHeaders['x-xss-protection']) {
            $vulnerabilities[] = "Missing X-XSS-Protection header";
            $recommendations[] = "Add X-XSS-Protection: 1; mode=block header";
        }
        
        if (!$sslValid) {
            $vulnerabilities[] = "Invalid or missing SSL certificate";
            $recommendations[] = "Install valid SSL certificate and redirect HTTP to HTTPS";
        } elseif (!$securityHeaders['strict-transport-security']) {
            $vulnerabilities[] = "Missing HSTS header on HTTPS site";
            $recommendations[] = "Add Strict-Transport-Security header for HTTPS enforcement";
        }
        
        if (!$securityHeaders['content-security-policy']) {
            $vulnerabilities[] = "Missing Content Security Policy";
            $recommendations[] = "Implement Content Security Policy to prevent XSS attacks";
        }
        
        // Check server signature
        if (isset($parsedHeaders['server'])) {
            $server = $parsedHeaders['server'];
            if (preg_match('/\d+\.\d+/', $server)) {
                $vulnerabilities[] = "Server version exposed in headers";
                $recommendations[] = "Hide server version information in HTTP headers";
            }
        }
        
        // Check for powered-by headers
        if (isset($parsedHeaders['x-powered-by'])) {
            $vulnerabilities[] = "Technology stack exposed via X-Powered-By header";
            $recommendations[] = "Remove X-Powered-By header to hide technology stack";
        }
        
        // Calculate security score
        $score = 100;
        $score -= count($vulnerabilities) * 5; // Deduct 5 points per vulnerability
        $score -= (7 - array_sum($securityHeaders)) * 8; // Deduct 8 points per missing security header
        if (!$sslValid) $score -= 20; // Major deduction for SSL issues
        $score = max(0, $score);
        
        $results[] = [
            'url' => $pageUrl,
            'status' => 'Success',
            'ssl_valid' => $sslValid,
            'security_headers' => $securityHeaders,
            'vulnerabilities' => $vulnerabilities,
            'recommendations' => $recommendations,
            'security_score' => $score,
            'server_info' => isset($parsedHeaders['server']) ? $parsedHeaders['server'] : 'Unknown',
            'powered_by' => isset($parsedHeaders['x-powered-by']) ? $parsedHeaders['x-powered-by'] : null
        ];
    }
    
    // Calculate summary statistics
    $totalPages = count($results);
    $successfulScans = count(array_filter($results, function($r) { return $r['status'] === 'Success'; }));
    $pagesWithSSL = count(array_filter($results, function($r) { return $r['ssl_valid']; }));
    $averageScore = $successfulScans > 0 ? array_sum(array_column($results, 'security_score')) / $successfulScans : 0;
    $totalVulnerabilities = array_sum(array_map(function($r) { return count($r['vulnerabilities']); }, $results));
    $highRiskPages = count(array_filter($results, function($r) { return $r['security_score'] < 50; }));
    $securePages = count(array_filter($results, function($r) { return $r['security_score'] >= 80; }));
    
    echo json_encode([
        'results' => $results,
        'exposed_files' => $exposedFiles,
        'summary' => [
            'total_pages' => $totalPages,
            'successful_scans' => $successfulScans,
            'pages_with_ssl' => $pagesWithSSL,
            'average_score' => round($averageScore, 1),
            'total_vulnerabilities' => $totalVulnerabilities,
            'high_risk_pages' => $highRiskPages,
            'secure_pages' => $securePages,
            'exposed_files_count' => count($exposedFiles)
        ]
    ]);
    exit;
}

function getRiskLevel($filename) {
    $highRisk = ['.htaccess', 'wp-config.php', 'config.php', '.env', 'backup.sql', 'database.sql', '.git/config'];
    $mediumRisk = ['phpinfo.php', 'info.php', 'test.php', 'admin.php', 'login.php'];
    $lowRisk = ['composer.json', 'package.json', 'robots.txt', 'sitemap.xml'];
    
    if (in_array($filename, $highRisk)) return 'High';
    if (in_array($filename, $mediumRisk)) return 'Medium';
    if (in_array($filename, $lowRisk)) return 'Low';
    return 'Medium';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MW Security Scanner – Sitemap Risk Review</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Evaluate sitemap URLs for security signals, mixed content, and vulnerable assets with the BREN7 MW Security Scanner.">
  <meta name="keywords" content="security sitemap scanner, mixed content checker, vulnerability audit, website security review, BREN7 security tool">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="MW Security Scanner – Sitemap Risk Review">
  <meta property="og:description" content="Scan sitemap endpoints for SSL issues, redirects, and exposed files using the BREN7 MW Security Scanner.">
  <meta property="og:url" content="https://bren7.com/apps/sitemap-security-scanner.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="MW Security Scanner – Sitemap Risk Review">
  <meta name="twitter:description" content="Audit sitemap URLs for security gaps with the MW Security Scanner by BREN7.">
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

    /* Security-specific colors */
    --security-high: #DC2626;
    --security-medium: #F59E0B;
    --security-low: #059669;
    --security-secure: #22C55E;

    /* Hover & fade states */
    --btn-orange-hover: #D97706;
    --fade-primary-blue: rgba(37, 99, 235, 0.04);
    --fade-secondary-blue: rgba(59, 130, 246, 0.1);
    --fade-accent-green: rgba(16, 185, 129, 0.1);
    --fade-error-red: rgba(239, 68, 68, 0.1);
    --fade-success-green: rgba(34, 197, 94, 0.1);
    --fade-light-gray: rgba(248, 250, 252, 0.5);
    --fade-security-high: rgba(220, 38, 38, 0.1);
    --fade-security-medium: rgba(245, 158, 11, 0.1);
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
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--dark-gray);
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

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
}
.checkbox-group input[type="checkbox"] {
    width: auto;
    margin: 0;
}
.checkbox-group label {
    margin: 0;
    font-size: 0.9rem;
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

/* Security Alert */
.security-alert {
    background: var(--fade-security-high);
    color: var(--security-high);
    border: 1px solid rgba(220, 38, 38, 0.2);
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
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

/* Links */
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

/* Security Badges */
.security-score {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
    min-width: 3rem;
    color: var(--white);
}
.score-secure { background: var(--security-secure); }
.score-good { background: var(--security-low); }
.score-medium { background: var(--security-medium); }
.score-poor { background: var(--security-high); }

.ssl-badge {
    padding: 0.2rem 0.4rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
}
.ssl-valid { background: var(--security-secure); color: var(--white); }
.ssl-invalid { background: var(--security-high); color: var(--white); }

.risk-badge {
    padding: 0.2rem 0.4rem;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--white);
}
.risk-high { background: var(--security-high); }
.risk-medium { background: var(--security-medium); }
.risk-low { background: var(--security-low); }

/* Vulnerability and Recommendation Items */
.vulnerability-item {
    background: var(--fade-security-high);
    color: var(--security-high);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.75rem;
    margin: 0.1rem;
    display: inline-block;
}
.recommendation-item {
    background: var(--fade-secondary-blue);
    color: var(--secondary-blue);
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.75rem;
    margin: 0.1rem;
    display: inline-block;
}

/* Security Header Status */
.header-status {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
}
.header-present {
    background: var(--fade-success-green);
    color: var(--success-green);
    padding: 0.1rem 0.3rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
}
.header-missing {
    background: var(--fade-error-red);
    color: var(--error-red);
    padding: 0.1rem 0.3rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
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
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/app-theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&family=Raleway:wght@400;700&display=swap" rel="stylesheet">

</head>
<body class="app-page">
  <div class="grid-background"></div>
  <div class="main-wrapper app-wrapper">
    <header class="app-header">
      <div class="header-content">
        <div class="logo-wrapper">
          <div class="logo">BREN<span class="accent">7</span></div>
          <div class="logo-underline"></div>
        </div>
        <p class="tagline">Web Tools & Experiments</p>
      </div>
    </header>
    <main class="app-main">
      <div class="app-content">

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shield-alt"></i> MW Security Scanner</h1>
            <p class="subtitle">Comprehensive security audit for HTTP headers, SSL configuration, exposed files, and common vulnerabilities</p>
        </div>

        <div class="card">
            <h2><i class="fas fa-info-circle"></i> About This Tool</h2>
            <p>The Security Scanner performs comprehensive security audits across your sitemap, checking for SSL configuration, security headers, exposed files, mixed content issues, and common web vulnerabilities. It provides actionable recommendations to improve your site's security posture.</p>
            
            <div class="security-alert">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Security Notice:</strong> This tool performs basic security checks only. For comprehensive security testing, consider professional penetration testing services.
                </div>
            </div>
        </div>

        <div class="card instructions">
            <h2><i class="fas fa-list-ol"></i> How It Works</h2>
            <ol>
                <li>Enter your site's complete sitemap URL (e.g., <code>https://example.com/sitemap.xml</code>)</li>
                <li>Choose how many pages to scan (limit: 25 pages for security scanning)</li>
                <li>Optionally enable exposed file checking to scan for common vulnerable files</li>
                <li>Click <strong>Start Security Scan</strong> to begin comprehensive analysis</li>
                <li>Review security scores, vulnerabilities, and implement recommended fixes</li>
            </ol>
        </div>

        <div class="card form-section">
            <h2><i class="fas fa-cog"></i> Scan Configuration</h2>
            <form id="security-form">
                <div class="form-group">
                    <label for="sitemap-url">Sitemap URL</label>
                    <div class="form-row">
                        <input type="url" id="sitemap-url" name="sitemap" placeholder="https://example.com/sitemap.xml" required>
                        <div>
                            <label for="max-pages" style="margin-bottom: 0.25rem;">Pages to Scan</label>
                            <select id="max-pages" name="max_pages">
                                <option value="5">5 pages</option>
                                <option value="10" selected>10 pages</option>
                                <option value="15">15 pages</option>
                                <option value="20">20 pages</option>
                                <option value="25">25 pages</option>
                            </select>
                        </div>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="check-exposed-files" name="check_exposed_files" value="1">
                        <label for="check-exposed-files">Check for exposed sensitive files (e.g., .htaccess, wp-config.php, .env)</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="scan-btn">
                    <i class="fas fa-shield-alt"></i>
                    Start Security Scan
                </button>
            </form>
        </div>

        <div id="status" class="status"></div>

        <div class="results-container" id="results-container">
            <div class="card summary-section" id="summary-section">
                <h2><i class="fas fa-chart-bar"></i> Security Summary</h2>
                <div class="stats-grid" id="stats-grid"></div>
            </div>

            <div class="card" id="exposed-files-section" style="display: none;">
                <h2><i class="fas fa-exclamation-triangle"></i> Exposed Files Found</h2>
                <div class="table-wrapper">
                    <table id="exposed-files-table">
                        <thead>
                            <tr>
                                <th>File Path</th>
                                <th>Risk Level</th>
                                <th>Status</th>
                                <th>Action Required</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h2><i class="fas fa-table"></i> Detailed Security Results</h2>
                <div class="table-wrapper">
                    <table id="results-table">
                        <thead>
                            <tr>
                                <th>Page URL</th>
                                <th>Security Score</th>
                                <th>SSL Status</th>
                                <th>Security Headers</th>
                                <th>Vulnerabilities</th>
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
    $('#security-form').on('submit', function(e) {
        e.preventDefault();
        var sitemapUrl = $('#sitemap-url').val().trim();
        var maxPages = $('#max-pages').val();
        var checkExposedFiles = $('#check-exposed-files').is(':checked') ? '1' : '0';
        
        if (!sitemapUrl) return;

        // Show loading state
        var loadingMessage = 'Performing security scan on ' + maxPages + ' pages';
        if (checkExposedFiles === '1') {
            loadingMessage += ' and checking for exposed files';
        }
        loadingMessage += '… this may take several minutes...';
        
        $('#status').removeClass('error success').addClass('loading').html('<div class="spinner"></div> ' + loadingMessage).show();
        $('#scan-btn').prop('disabled', true).html('<div class="spinner"></div> Scanning...');
        $('#results-container').hide();

        $.post('<?php echo $_SERVER["PHP_SELF"]; ?>', { 
            sitemap: sitemapUrl,
            max_pages: maxPages,
            check_exposed_files: checkExposedFiles
        })
         .done(function(data) {
            $('#scan-btn').prop('disabled', false).html('<i class="fas fa-shield-alt"></i> Start Security Scan');
            
            if (data.error) {
                $('#status').removeClass('loading success').addClass('error').text(data.error);
                return;
            }

            var results = data.results || [];
            var summary = data.summary || {};
            var exposedFiles = data.exposed_files || [];
            
            if (!results.length) {
                $('#status').removeClass('loading error').addClass('success').text('Security scan completed successfully, but no pages were analyzed.');
                return;
            }

            var statusMessage = 'Security scan completed! Analyzed ' + summary.total_pages + ' pages with an average security score of ' + summary.average_score + '/100.';
            if (exposedFiles.length > 0) {
                statusMessage += ' WARNING: ' + exposedFiles.length + ' exposed files found!';
                $('#status').removeClass('loading success').addClass('error').text(statusMessage);
            } else {
                $('#status').removeClass('loading error').addClass('success').text(statusMessage);
            }

            // Populate summary stats
            var $statsGrid = $('#stats-grid');
            $statsGrid.html(
                '<div class="stat-card"><span class="stat-number">' + summary.total_pages + '</span><div class="stat-label">Pages Scanned</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.average_score + '</span><div class="stat-label">Average Security Score</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.pages_with_ssl + '</span><div class="stat-label">Pages with Valid SSL</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.secure_pages + '</span><div class="stat-label">Secure Pages (≥80)</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.high_risk_pages + '</span><div class="stat-label">High Risk Pages (<50)</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.total_vulnerabilities + '</span><div class="stat-label">Total Vulnerabilities</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + summary.exposed_files_count + '</span><div class="stat-label">Exposed Files</div></div>'
            );

            // Show exposed files table if any found
            if (exposedFiles.length > 0) {
                var $exposedTbody = $('#exposed-files-table tbody');
                $exposedTbody.empty();
                
                exposedFiles.forEach(function(file) {
                    var actionRequired = '';
                    switch(file.risk) {
                        case 'High':
                            actionRequired = 'IMMEDIATE: Block access or remove file';
                            break;
                        case 'Medium':
                            actionRequired = 'Review and secure or remove if unnecessary';
                            break;
                        case 'Low':
                            actionRequired = 'Review content and restrict if needed';
                            break;
                    }
                    
                    $exposedTbody.append(
                        $('<tr>').append(
                            $('<td>').html('<code>' + file.file + '</code>'),
                            $('<td>').html('<span class="risk-badge risk-' + file.risk.toLowerCase() + '">' + file.risk + '</span>'),
                            $('<td>').text(file.status),
                            $('<td>').text(actionRequired)
                        )
                    );
                });
                
                $('#exposed-files-section').show();
            }

            // Populate detailed results table
            var $tbody = $('#results-table tbody');
            $tbody.empty();
            
            results.forEach(function(result) {
                if (result.status === 'Error') {
                    $tbody.append(
                        $('<tr>').append(
                            $('<td>').append($('<a>').addClass('page-link').attr('href', result.url).attr('target', '_blank').html('<i class="fas fa-external-link-alt" style="font-size: 0.8em; opacity: 0.7;"></i> ' + (result.url.length > 40 ? result.url.substring(0, 40) + '...' : result.url))),
                            $('<td>').html('<span class="security-score score-poor">Error</span>'),
                            $('<td>').html('<span class="ssl-badge ssl-invalid">Unknown</span>'),
                            $('<td>').text('--'),
                            $('<td>').html('<span class="vulnerability-item">Failed to scan</span>'),
                            $('<td>').text('Check URL accessibility')
                        )
                    );
                    return;
                }

                // Security score styling
                var scoreClass = 'score-poor';
                if (result.security_score >= 80) scoreClass = 'score-secure';
                else if (result.security_score >= 60) scoreClass = 'score-good';
                else if (result.security_score >= 40) scoreClass = 'score-medium';

                // SSL status
                var sslBadge = result.ssl_valid ? 
                    '<span class="ssl-badge ssl-valid">Valid</span>' : 
                    '<span class="ssl-badge ssl-invalid">Invalid</span>';

                // Security headers status
                var headersHtml = '';
                if (result.security_headers) {
                    var headers = result.security_headers;
                    headersHtml = '<div class="header-status">';
                    Object.keys(headers).forEach(function(header) {
                        var shortName = header.split('-').map(function(word) {
                            return word.charAt(0).toUpperCase();
                        }).join('');
                        if (headers[header]) {
                            headersHtml += '<span class="header-present">' + shortName + '</span>';
                        } else {
                            headersHtml += '<span class="header-missing">' + shortName + '</span>';
                        }
                    });
                    headersHtml += '</div>';
                }

                // Format vulnerabilities
                var vulnerabilitiesHtml = '';
                if (result.vulnerabilities && result.vulnerabilities.length > 0) {
                    vulnerabilitiesHtml = result.vulnerabilities.slice(0, 3).map(function(vuln) {
                        return '<span class="vulnerability-item">' + (vuln.length > 40 ? vuln.substring(0, 40) + '...' : vuln) + '</span>';
                    }).join(' ');
                    if (result.vulnerabilities.length > 3) {
                        vulnerabilitiesHtml += '<span class="vulnerability-item">+' + (result.vulnerabilities.length - 3) + ' more</span>';
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
                        $('<td>').html('<span class="security-score ' + scoreClass + '">' + result.security_score + '</span>'),
                        $('<td>').html(sslBadge),
                        $('<td>').html(headersHtml),
                        $('<td>').html(vulnerabilitiesHtml || '<span style="color: var(--success-green); font-size: 0.8rem;">No issues</span>'),
                        $('<td>').html(recommendationsHtml || '<span style="color: var(--success-green); font-size: 0.8rem;">Well secured</span>')
                    )
                );
            });

            $('#results-container').show();
        })
         .fail(function(xhr, status, error) {
            $('#scan-btn').prop('disabled', false).html('<i class="fas fa-shield-alt"></i> Start Security Scan');
            $('#status').removeClass('loading success').addClass('error').text('Request failed: ' + error);
        });
    });
    </script>
      </div>
    </main>
    <footer class="app-footer">
      <div class="footer-content">
        <div class="social-links">
          <a href="https://discordapp.com/users/Bliss#6318" class="social-link" target="_blank" rel="noopener" aria-label="Discord">
            <i class="fab fa-discord"></i>
            <span>Discord</span>
          </a>
          <a href="https://github.com/brentjlaf" class="social-link" target="_blank" rel="noopener" aria-label="GitHub">
            <i class="fab fa-github"></i>
            <span>GitHub</span>
          </a>
        </div>
        <div class="footer-info">
          <p>&copy; <span id="current-year"></span> BREN7. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
  <script>document.getElementById('current-year').textContent = new Date().getFullYear();</script>

</body>
</html>