<?php
// sitemap_exporter.php
// Enhanced sitemap-to-CSV exporter with preview table and copy functionality

/**
 * Fetches the content of a URL using file_get_contents or cURL as a fallback.
 */
function fetchUrlContent(string $url) {
    if (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create([ 'http' => [ 'header' => "User-Agent: SitemapExporterBot" ] ]);
        $data = @file_get_contents($url, false, $ctx);
        if ($data !== false) return $data;
    }
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SitemapExporterBot');
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data !== false) return $data;
    }
    return false;
}

/**
 * Recursively parses a sitemap (including gzipped and index sitemaps) to extract all URLs.
 */
function getSitemapUrls(string $sitemapUrl): array {
    $raw = fetchUrlContent($sitemapUrl);
    if (!$raw) return [];
    if (substr($sitemapUrl, -3) === '.gz') {
        $decoded = @gzdecode($raw);
        if ($decoded !== false) $raw = $decoded;
    }
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($raw);
    libxml_clear_errors();
    if (!$xml) return [];

    $urls = [];
    // <urlset>
    foreach ($xml->url as $url) {
        $urls[] = (string)$url->loc;
    }
    // <sitemapindex>
    if (empty($urls) && isset($xml->sitemap)) {
        foreach ($xml->sitemap as $sm) {
            $sub = getSitemapUrls((string)$sm->loc);
            if ($sub) $urls = array_merge($urls, $sub);
        }
    }
    return array_unique($urls);
}

/**
 * Fetches and parses a page to extract URL, <title>, meta[name=description], and meta[name=title] or og:title.
 */
function fetchPageData(string $url): ?array {
    $html = fetchUrlContent($url);
    if (!$html) return null;
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html);
    libxml_clear_errors();

    // Page <title>
    $titleNode = $doc->getElementsByTagName('title')->item(0);
    $pageTitle = $titleNode ? $titleNode->nodeValue : '';

    // Meta description & meta title
    $metaDesc = '';
    $metaTitle = '';
    foreach ($doc->getElementsByTagName('meta') as $meta) {
        $name = strtolower($meta->getAttribute('name'));
        $property = strtolower($meta->getAttribute('property'));
        if ($name === 'description') {
            $metaDesc = $meta->getAttribute('content');
        }
        if ($name === 'title') {
            $metaTitle = $meta->getAttribute('content');
        }
        if (!$metaTitle && $property === 'og:title') {
            $metaTitle = $meta->getAttribute('content');
        }
    }

    return [
        $url,
        trim($pageTitle),
        trim($metaDesc),
        trim($metaTitle)
    ];
}

$error = '';
$results = [];
$sitemap = '';

// Handle CSV download
if (isset($_GET['download']) && isset($_GET['sitemap'])) {
    $sitemap = $_GET['sitemap'];
    $urls = getSitemapUrls($sitemap);
    
    if (!empty($urls)) {
        $parsed = parse_url($sitemap);
        $host = $parsed['host'] ?? 'site';
        date_default_timezone_set('America/Edmonton');
        $timestamp = date('Y-m-d_H-i-s');
        $filename = $host . '_' . $timestamp . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['URL', 'Title', 'Meta Description', 'Meta Title']);

        foreach ($urls as $url) {
            $row = fetchPageData($url);
            if ($row) {
                fputcsv($out, $row);
                flush();
                sleep(1);
            }
        }

        fclose($out);
        exit;
    }
}

// Handle form submission for preview
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sitemap = trim($_POST['sitemap_url'] ?? '');
    if (!$sitemap) {
        $error = 'Please enter a sitemap URL.';
    } else {
        $urls = getSitemapUrls($sitemap);
        if (empty($urls)) {
            $error = 'No URLs found or failed to load sitemap.';
        } else {
            foreach ($urls as $url) {
                $row = fetchPageData($url);
                if ($row) {
                    $results[] = $row;
                }
                // Add a small delay to prevent overwhelming the server
                usleep(500000); // 0.5 seconds
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap to CSV Exporter – Advanced Insights</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Transform sitemap XML into CSV files enriched with metadata, canonical tags, and analytics-ready fields using BREN7's advanced exporter.">
  <meta name="keywords" content="advanced sitemap exporter, metadata csv, seo data extraction, url export tool, BREN7 sitemap">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Sitemap to CSV Exporter – Advanced Insights">
  <meta property="og:description" content="Export sitemap URLs with detailed metadata and headings using the advanced sitemap exporter by BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/sitemap-exporter-advanced.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Sitemap to CSV Exporter – Advanced Insights">
  <meta name="twitter:description" content="Export enhanced sitemap data with the advanced CSV exporter from BREN7.">
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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #F5F5F5;
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        padding: 48px;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .results-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        padding: 48px;
        width: 100%;
        max-width: 96%;
        margin: 40px auto;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .header {
        text-align: center;
        margin-bottom: 40px;
    }

    .header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #00BFA5;
        margin-bottom: 12px;
    }

    .header p {
        color: #333333;
        font-size: 1.1rem;
        font-weight: 400;
    }

    .form-group {
        margin-bottom: 32px;
    }

    label {
        display: block;
        font-weight: 600;
        color: #333333;
        margin-bottom: 12px;
        font-size: 1rem;
    }

    .input-wrapper {
        position: relative;
    }

    input[type="text"] {
        width: 100%;
        padding: 20px 24px;
        font-size: 16px;
        border: 2px solid #F5F5F5;
        border-radius: 16px;
        background: #FFFFFF;
        transition: all 0.3s ease;
        color: #333333;
        font-family: inherit;
    }

    input[type="text"]:focus {
        outline: none;
        border-color: #00BFA5;
        box-shadow: 0 0 0 4px rgba(0, 191, 165, 0.1);
        transform: translateY(-2px);
    }

    input[type="text"]::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }

    .action-btn {
        padding: 20px 24px;
        font-size: 16px;
        font-weight: 600;
        color: #FFFFFF;
        background: #00BFA5;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-block;
        min-width: 160px;
        text-align: center;
    }

    .submit-btn {
        width: 100%;
        padding: 20px 24px;
        font-size: 16px;
        font-weight: 600;
        color: #FFFFFF;
        background: #00BFA5;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
        position: relative;
        overflow: hidden;
    }

    .action-btn.secondary {
        background: #FFB800;
    }

    .submit-btn:hover, .action-btn:hover {
        background: #FFB800;
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 191, 165, 0.3);
    }

    .action-btn.secondary:hover {
        background: #00BFA5;
    }

    .error {
        background: #FED7D7;
        color: #C53030;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 500;
        border-left: 4px solid #E53E3E;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .error::before {
        content: '⚠️';
        font-size: 1.2rem;
    }

    .success {
        background: #C6F6D5;
        color: #22543D;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 500;
        border-left: 4px solid #38A169;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .success::before {
        content: '✅';
        font-size: 1.2rem;
    }

    .results-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 24px;
        background: rgba(17, 27, 45, 0.9);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 25px 45px rgba(0, 0, 0, 0.35);
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    .results-table th,
    .results-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #E2E8F0;
        vertical-align: top;
    }

    .results-table th {
        background: #00BFA5;
        color: white;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .results-table tr:hover {
        background: rgba(92, 204, 244, 0.12);
    }

    .results-table td {
        word-break: break-word;
        max-width: 300px;
    }

    .url-cell {
        font-family: monospace;
        font-size: 14px;
        color: #00BFA5;
    }

    .table-container {
        max-height: 600px;
        overflow-y: auto;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(15, 22, 38, 0.85);
    }

    .actions {
        margin-top: 24px;
        text-align: center;
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .copy-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #48BB78;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .copy-notification.show {
        opacity: 1;
        transform: translateY(0);
    }

    .loading {
        display: none;
        text-align: center;
        color: #00BFA5;
        font-weight: 500;
        margin-top: 16px;
    }

    .loading.show {
        display: block;
    }

    .stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding: 16px;
        background: #F7FAFC;
        border-radius: 12px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #00BFA5;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #718096;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .container, .results-container {
            padding: 24px;
            margin: 20px;
        }

        .header h1 {
            font-size: 2rem;
        }

        .results-table th,
        .results-table td {
            padding: 8px 12px;
            font-size: 14px;
        }

        .stats {
            flex-direction: column;
            gap: 16px;
        }

        .actions {
            gap: 12px;
        }

        .action-btn {
            width: 100%;
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
            <h1>Sitemap Exporter</h1>
            <p>Transform your sitemap into a comprehensive CSV report</p>
        </div>

        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="" id="exportForm">
            <div class="form-group">
                <label for="sitemap_url">Sitemap URL</label>
                <div class="input-wrapper">
                    <input 
                        type="text" 
                        id="sitemap_url" 
                        name="sitemap_url" 
                        placeholder="https://example.com/sitemap.xml"
                        value="<?= htmlspecialchars($_POST['sitemap_url'] ?? '') ?>" 
                        required
                    >
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span id="btnText">Analyze Sitemap</span>
            </button>

            <div class="loading" id="loading">
                Processing sitemap... This may take a few minutes for large sites.
            </div>
        </form>
    </div>

    <?php if (!empty($results)): ?>
    <div class="results-container">
        <div class="success">
            Successfully analyzed <?= count($results) ?> pages from your sitemap!
        </div>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-number"><?= count($results) ?></div>
                <div class="stat-label">Pages Found</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= count(array_filter($results, function($r) { return !empty($r[1]); })) ?></div>
                <div class="stat-label">With Titles</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= count(array_filter($results, function($r) { return !empty($r[2]); })) ?></div>
                <div class="stat-label">With Descriptions</div>
            </div>
        </div>

        <div class="actions">
            <a href="?download=1&sitemap=<?= urlencode($sitemap) ?>" class="action-btn">
                Download CSV
            </a>
            <button onclick="copyTableToClipboard()" class="action-btn secondary">
                Copy Table
            </button>
        </div>

        <div class="table-container">
            <table class="results-table" id="resultsTable">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>Page Title</th>
                        <th>Meta Description</th>
                        <th>Meta Title</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                    <tr>
                        <td class="url-cell"><?= htmlspecialchars($row[0]) ?></td>
                        <td><?= htmlspecialchars($row[1]) ?></td>
                        <td><?= htmlspecialchars($row[2]) ?></td>
                        <td><?= htmlspecialchars($row[3]) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <div class="copy-notification" id="copyNotification">
        Table copied to clipboard!
    </div>

    <script>
        document.getElementById('exportForm').addEventListener('submit', function() {
            const loading = document.getElementById('loading');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            
            loading.classList.add('show');
            submitBtn.disabled = true;
            btnText.textContent = 'Processing...';
            submitBtn.style.opacity = '0.7';
        });

        function copyTableToClipboard() {
            const table = document.getElementById('resultsTable');
            let csvContent = '';
            
            // Get headers
            const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent);
            csvContent += headers.join('\t') + '\n';
            
            // Get data rows
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td')).map(td => td.textContent);
                csvContent += cells.join('\t') + '\n';
            });
            
            // Copy to clipboard
            navigator.clipboard.writeText(csvContent).then(function() {
                showCopyNotification();
            }).catch(function(err) {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = csvContent;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showCopyNotification();
            });
        }

        function showCopyNotification() {
            const notification = document.getElementById('copyNotification');
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Add subtle animations to form elements
        const input = document.getElementById('sitemap_url');
        input.addEventListener('input', function() {
            if (this.value.length > 0) {
                this.style.borderColor = '#667eea';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
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