<?php
/**
 * Standalone Accessibility Scanner
 * Drop this file into /accessibility/ folder in your site root
 * Access via: https://yoursite.com/accessibility/
 */

// Start session for storing results
session_start();

// Configuration
$maxPagesToScan = 500;
$requestTimeout = 10;
$delayBetweenRequests = 100000; // 0.1 seconds in microseconds

// Check if custom sitemap URL is provided
if (isset($_POST['sitemap_url']) && !empty($_POST['sitemap_url'])) {
    $sitemapUrl = filter_var($_POST['sitemap_url'], FILTER_SANITIZE_URL);
    $_SESSION['custom_sitemap_url'] = $sitemapUrl;
} elseif (isset($_SESSION['custom_sitemap_url'])) {
    $sitemapUrl = $_SESSION['custom_sitemap_url'];
} else {
    // Auto-detect site URL
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $siteUrl = $protocol . '://' . $host;
    
    // Remove /accessibility from the path to get site root
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    $siteBaseUrl = $siteUrl . str_replace('/accessibility', '', $scriptPath);
    $sitemapUrl = $siteBaseUrl . '/sitemap.xml';
}

// Extract site name from sitemap URL for filename
$parsedUrl = parse_url($sitemapUrl);
$siteName = isset($parsedUrl['host']) ? preg_replace('/^www\./', '', $parsedUrl['host']) : 'site';

/**
 * Fetch and parse sitemap.xml recursively
 */
function fetch_sitemap(string $url, int $depth = 0, int $maxDepth = 3): array {
    if ($depth > $maxDepth) {
        return [];
    }
    
    $urls = [];
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'Mozilla/5.0 (Accessibility Scanner)',
            'follow_location' => true
        ]
    ]);
    
    $content = @file_get_contents($url, false, $context);
    if ($content === false) {
        return $urls;
    }
    
    libxml_use_internal_errors(true);
    $xml = @simplexml_load_string($content);
    libxml_clear_errors();
    
    if ($xml === false) {
        return $urls;
    }
    
    // Parse URL entries
    foreach ($xml->children() as $child) {
        if ($child->getName() === 'url' && isset($child->loc)) {
            $urls[] = (string)$child->loc;
        }
    }
    
    // Parse sitemap index
    foreach ($xml->children() as $child) {
        if ($child->getName() === 'sitemap' && isset($child->loc)) {
            $childUrls = fetch_sitemap((string)$child->loc, $depth + 1, $maxDepth);
            $urls = array_merge($urls, $childUrls);
        }
    }
    
    return array_unique($urls);
}

/**
 * Fetch page content
 */
function fetch_page(string $url, int $timeout = 10): ?string {
    $context = stream_context_create([
        'http' => [
            'timeout' => $timeout,
            'user_agent' => 'Mozilla/5.0 (Accessibility Scanner)',
            'follow_location' => true
        ]
    ]);
    
    $content = @file_get_contents($url, false, $context);
    return $content !== false ? $content : null;
}

/**
 * Extract title from HTML
 */
function extract_title(string $html): string {
    if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches)) {
        return trim(html_entity_decode(strip_tags($matches[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
    return 'Untitled';
}

/**
 * Analyze page accessibility
 */
function analyze_page(string $html): array {
    $doc = new DOMDocument();
    $loaded = @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOERROR);
    
    $results = [
        'images' => 0,
        'missing_alt' => 0,
        'h1_count' => 0,
        'h2_count' => 0,
        'generic_links' => 0,
        'landmarks' => 0,
        'issues' => []
    ];
    
    if (!$loaded) {
        return $results;
    }
    
    // Analyze images
    $images = $doc->getElementsByTagName('img');
    $results['images'] = $images->length;
    foreach ($images as $img) {
        if (trim($img->getAttribute('alt')) === '') {
            $results['missing_alt']++;
        }
    }
    
    // Analyze headings
    $results['h1_count'] = $doc->getElementsByTagName('h1')->length;
    $results['h2_count'] = $doc->getElementsByTagName('h2')->length;
    
    // Analyze links
    $genericTerms = ['click here', 'read more', 'learn more', 'here', 'more', 'this page'];
    $anchors = $doc->getElementsByTagName('a');
    foreach ($anchors as $anchor) {
        $text = strtolower(trim($anchor->textContent));
        if (in_array($text, $genericTerms)) {
            $results['generic_links']++;
        }
    }
    
    // Count landmarks
    foreach (['main', 'nav', 'header', 'footer'] as $tag) {
        $results['landmarks'] += $doc->getElementsByTagName($tag)->length;
    }
    
    // Generate issues list
    if ($results['missing_alt'] > 0) {
        $results['issues'][] = [
            'type' => 'critical',
            'message' => $results['missing_alt'] . ' image' . ($results['missing_alt'] === 1 ? '' : 's') . ' missing alt text'
        ];
    }
    
    if ($results['h1_count'] === 0) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => 'No H1 heading found'
        ];
    } elseif ($results['h1_count'] > 1) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => 'Multiple H1 headings detected (' . $results['h1_count'] . ')'
        ];
    }
    
    if ($results['generic_links'] > 0) {
        $results['issues'][] = [
            'type' => 'moderate',
            'message' => $results['generic_links'] . ' link' . ($results['generic_links'] === 1 ? '' : 's') . ' with generic text'
        ];
    }
    
    if ($results['landmarks'] === 0) {
        $results['issues'][] = [
            'type' => 'minor',
            'message' => 'No semantic landmarks (main, nav, header, footer)'
        ];
    }
    
    return $results;
}

/**
 * Calculate accessibility score
 */
function calculate_score(array $analysis): array {
    $critical = $analysis['missing_alt'];
    $serious = ($analysis['h1_count'] === 0 || $analysis['h1_count'] > 1) ? 1 : 0;
    $moderate = $analysis['generic_links'] > 0 ? 1 : 0;
    $minor = $analysis['landmarks'] === 0 ? 1 : 0;
    
    $score = 100;
    $score -= $critical * 15;
    $score -= $serious * 12;
    $score -= $moderate * 8;
    $score -= $minor * 5;
    
    $totalViolations = $critical + $serious + $moderate + $minor;
    if ($totalViolations === 0) {
        $score = 98;
    }
    $score = max(0, min(100, $score));
    
    // Determine WCAG level
    if ($totalViolations === 0) {
        $level = 'AAA';
    } elseif ($critical === 0 && $serious <= 1 && $score >= 80) {
        $level = 'AA';
    } elseif ($score >= 60) {
        $level = 'Partial';
    } else {
        $level = 'Failing';
    }
    
    return [
        'score' => $score,
        'level' => $level,
        'violations' => [
            'critical' => $critical,
            'serious' => $serious,
            'moderate' => $moderate,
            'minor' => $minor,
            'total' => $totalViolations
        ]
    ];
}

/**
 * Export results to CSV
 */
function export_csv(array $results, string $siteName): void {
    $filename = preg_replace('/[^a-z0-9]/i', '-', $siteName);
    $filename .= '-accessibility-report-' . date('Y-m-d-His') . '.csv';
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $output = fopen('php://output', 'w');
    
    // CSV headers
    fputcsv($output, [
        'URL',
        'Title',
        'Score',
        'WCAG Level',
        'Critical Issues',
        'Serious Issues',
        'Moderate Issues',
        'Minor Issues',
        'Total Violations',
        'Images',
        'Missing Alt',
        'H1 Count',
        'Generic Links',
        'Landmarks',
        'Issues Summary'
    ]);
    
    // Data rows
    foreach ($results as $page) {
        $issuesSummary = implode('; ', array_map(function($issue) {
            return $issue['message'];
        }, $page['issues']));
        
        fputcsv($output, [
            $page['url'],
            $page['title'],
            $page['score'],
            $page['level'],
            $page['violations']['critical'],
            $page['violations']['serious'],
            $page['violations']['moderate'],
            $page['violations']['minor'],
            $page['violations']['total'],
            $page['images'],
            $page['missing_alt'],
            $page['h1_count'],
            $page['generic_links'],
            $page['landmarks'],
            $issuesSummary
        ]);
    }
    
    fclose($output);
    exit;
}

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    if (isset($_SESSION['scan_results'])) {
        export_csv($_SESSION['scan_results'], $siteName);
    } else {
        die('No scan results available. Please run a scan first.');
    }
}

// Handle scan request
$scanResults = [];
$scanning = false;
$errorMessage = null;

if (isset($_POST['scan']) || isset($_GET['scan'])) {
    $scanning = true;
    
    // Fetch sitemap
    $urls = fetch_sitemap($sitemapUrl);
    
    if (empty($urls)) {
        $errorMessage = "Could not fetch or parse sitemap at: $sitemapUrl";
    } else {
        // Limit URLs
        if (count($urls) > $maxPagesToScan) {
            $urls = array_slice($urls, 0, $maxPagesToScan);
        }
        
        // Scan each page
        foreach ($urls as $url) {
            $html = fetch_page($url, $requestTimeout);
            
            if ($html === null) {
                continue;
            }
            
            $title = extract_title($html);
            $analysis = analyze_page($html);
            $scoreData = calculate_score($analysis);
            
            $scanResults[] = array_merge([
                'url' => $url,
                'title' => $title,
            ], $analysis, $scoreData);
            
            // Small delay between requests
            usleep($delayBetweenRequests);
        }
        
        // Store results in session for CSV export
        $_SESSION['scan_results'] = $scanResults;
    }
}

// Calculate summary statistics
$stats = [
    'total' => count($scanResults),
    'avg_score' => 0,
    'critical_issues' => 0,
    'aa_compliant' => 0,
    'failing' => 0
];

if (!empty($scanResults)) {
    $scoreSum = 0;
    foreach ($scanResults as $result) {
        $scoreSum += $result['score'];
        $stats['critical_issues'] += $result['violations']['critical'];
        if (in_array($result['level'], ['AA', 'AAA'])) {
            $stats['aa_compliant']++;
        }
        if ($result['level'] === 'Failing') {
            $stats['failing']++;
        }
    }
    $stats['avg_score'] = round($scoreSum / $stats['total']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accessibility Quick Scan – WCAG Issue Finder</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Run rapid accessibility checks across sitemap URLs and prioritize WCAG issues with the BREN7 Accessibility Quick Scan tool.">
  <meta name="keywords" content="accessibility scanner, wcag audit, quick compliance check, web accessibility report, BREN7 accessibility">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Accessibility Quick Scan – WCAG Issue Finder">
  <meta property="og:description" content="Surface WCAG violations, compliance levels, and critical accessibility issues using BREN7's quick scan dashboard.">
  <meta property="og:url" content="https://bren7.com/apps/accessibility-quick-scan.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Accessibility Quick Scan – WCAG Issue Finder">
  <meta name="twitter:description" content="Quickly assess WCAG issues across pages with the BREN7 Accessibility Quick Scan tool.">
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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: radial-gradient(circle at top left, rgba(31, 64, 104, 0.65), rgba(7, 12, 24, 0.95));
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        header {
            color: rgba(255, 255, 255, 0.95);
            padding: 3rem 2rem;
            border-radius: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 30px 60px rgba(0,0,0,0.35);
            border: 1px solid rgba(148, 163, 184, 0.25);
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .actions {
            margin-top: 1.5rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #5cccf4, #2d9ac0);
            color: #0d1424;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 15px 30px rgba(92,204,244,0.25);
        }

        .btn:hover,
        .btn:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79,70,229,0.35);
            background: #4338ca;
            color: #ffffff;
        }

        .btn:focus {
            outline: 3px solid rgba(255,255,255,0.6);
            outline-offset: 2px;
        }

        .btn-secondary {
            background: rgba(15,23,42,0.35);
            color: #f8fafc;
            border: 2px solid rgba(248,250,252,0.55);
        }

        .btn-secondary:hover,
        .btn-secondary:focus {
            background: rgba(15,23,42,0.55);
            border-color: rgba(248,250,252,0.75);
            color: #ffffff;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: rgba(17, 27, 45, 0.9);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 20px 45px rgba(0,0,0,0.35);
            border: 1px solid rgba(92,204,244,0.2);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #8ab4ff;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: rgba(255,255,255,0.65);
            font-size: 0.9rem;
        }
        
        .results {
            background: rgba(17, 27, 45, 0.88);
            border-radius: 1.25rem;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.35);
            border: 1px solid rgba(148,163,184,0.25);
        }
        
        .results h2 {
            margin-bottom: 1.5rem;
            color: rgba(255,255,255,0.92);
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: rgba(28, 40, 64, 0.85);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: rgba(255,255,255,0.82);
            border-bottom: 2px solid rgba(148,163,184,0.25);
            white-space: nowrap;
        }
        
        td {
            padding: 1rem;
            border-bottom: 1px solid rgba(148,163,184,0.2);
        }
        
        tr:hover {
            background: rgba(92, 204, 244, 0.12);
        }
        
        .score {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .score-aaa, .score-aa {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }
        
        .score-partial {
            background: rgba(251, 191, 36, 0.25);
            color: #facc15;
        }
        
        .score-failing {
            background: rgba(248, 113, 113, 0.25);
            color: #f87171;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-critical {
            background: rgba(248, 113, 113, 0.22);
            color: #fca5a5;
        }
        
        .badge-serious {
            background: rgba(249, 115, 22, 0.22);
            color: #fb923c;
        }
        
        .badge-moderate {
            background: rgba(234, 179, 8, 0.22);
            color: #facc15;
        }
        
        .badge-minor {
            background: rgba(96, 165, 250, 0.22);
            color: #93c5fd;
        }
        
        .issues-list {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.7);
            max-width: 400px;
        }
        
        .issues-list div {
            margin-bottom: 0.25rem;
        }
        
        .error {
            background: rgba(56, 6, 16, 0.75);
            color: #fca5a5;
            padding: 1.5rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(252, 165, 165, 0.35);
        }
        
        .loading {
            text-align: center;
            padding: 3rem;
        }
        
        .spinner {
            border: 4px solid rgba(255,255,255,0.15);
            border-top: 4px solid #5cccf4;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .url-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .url-cell a {
            color: #8ab4ff;
            text-decoration: none;
            font-weight: 600;
        }

        .url-cell a:hover {
            text-decoration: underline;
            color: #c4d9ff;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            h1 {
                font-size: 1.75rem;
            }
            
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            table {
                font-size: 0.875rem;
            }
            
            th, td {
                padding: 0.75rem 0.5rem;
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
        <header>
            <h1>Accessibility Scanner</h1>
            <p class="subtitle">WCAG 2.1 AA Compliance Analysis</p>
            
            <form method="POST" action="" style="margin-top: 1.5rem;">
                <div style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 300px;">
                        <label for="sitemap_url" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: rgba(255,255,255,0.75);">Sitemap URL:</label>
                        <input
                            type="url"
                            id="sitemap_url"
                            name="sitemap_url"
                            value="<?php echo htmlspecialchars($sitemapUrl); ?>"
                            placeholder="https://example.com/sitemap.xml"
                            style="width: 100%; padding: 0.75rem; border: 2px solid rgba(92,204,244,0.35); border-radius: 0.5rem; background: rgba(17,27,45,0.85); color: rgba(255,255,255,0.92); font-size: 1rem;"
                            required
                        >
                    </div>
                    <button type="submit" name="scan" value="1" class="btn" style="margin: 0;">Scan This Sitemap</button>
                </div>
            </form>
            
            <?php if (!empty($scanResults)): ?>
                <div class="actions" style="margin-top: 1rem;">
                    <a href="?export=csv" class="btn btn-secondary">Export CSV Report</a>
                    <a href="?" class="btn btn-secondary">Clear Results</a>
                </div>
            <?php endif; ?>
        </header>
        
        <?php if ($errorMessage): ?>
            <div class="error">
                <strong>Error:</strong> <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($scanning && !$errorMessage && empty($scanResults)): ?>
            <div class="loading">
                <div class="spinner"></div>
                <p>Scanning pages... This may take a few minutes.</p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($scanResults)): ?>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Pages Scanned</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['avg_score']; ?>%</div>
                    <div class="stat-label">Average Score</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['critical_issues']; ?></div>
                    <div class="stat-label">Critical Issues</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $stats['aa_compliant']; ?></div>
                    <div class="stat-label">AA/AAA Compliant</div>
                </div>
            </div>
            
            <div class="results">
                <h2>Scan Results</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Title</th>
                                <th>Score</th>
                                <th>Level</th>
                                <th>Issues</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scanResults as $result): ?>
                                <tr>
                                    <td class="url-cell">
                                        <a href="<?php echo htmlspecialchars($result['url']); ?>" target="_blank">
                                            <?php echo htmlspecialchars($result['url']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($result['title']); ?></td>
                                    <td>
                                        <span class="score score-<?php echo strtolower($result['level']); ?>">
                                            <?php echo $result['score']; ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($result['level']); ?>">
                                            <?php echo $result['level']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($result['violations']['critical'] > 0): ?>
                                            <span class="badge badge-critical"><?php echo $result['violations']['critical']; ?> Critical</span>
                                        <?php endif; ?>
                                        <?php if ($result['violations']['serious'] > 0): ?>
                                            <span class="badge badge-serious"><?php echo $result['violations']['serious']; ?> Serious</span>
                                        <?php endif; ?>
                                        <?php if ($result['violations']['moderate'] > 0): ?>
                                            <span class="badge badge-moderate"><?php echo $result['violations']['moderate']; ?> Moderate</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="issues-list">
                                            <?php foreach ($result['issues'] as $issue): ?>
                                                <div><?php echo htmlspecialchars($issue['message']); ?></div>
                                            <?php endforeach; ?>
                                            <?php if (empty($result['issues'])): ?>
                                                <div style="color: #065f46;">No issues detected</div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php elseif (!$scanning): ?>
            <div class="results">
                <h2>Getting Started</h2>
                <p style="margin-bottom: 1rem;">This tool will scan any website for accessibility issues based on WCAG 2.1 AA guidelines.</p>
                <p style="margin-bottom: 1rem;">Enter a sitemap URL above and click "Scan This Sitemap" to begin.</p>
                <ul style="margin-left: 1.5rem; color: #666;">
                    <li>Checks all pages in the sitemap</li>
                    <li>Analyzes images, headings, links, and semantic structure</li>
                    <li>Provides WCAG compliance ratings</li>
                    <li>Export results to CSV for further analysis</li>
                </ul>
                <p style="margin-top: 1.5rem; color: #666; font-size: 0.9rem;">
                    <strong>Examples:</strong><br>
                    • https://example.com/sitemap.xml<br>
                    • https://example.com/sitemap_index.xml<br>
                    • https://example.com/post-sitemap.xml
                </p>
            </div>
        <?php endif; ?>
    </div>
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