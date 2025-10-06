<?php
// sitemap_exporter.php

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
 * Fetches and parses a page to extract HTML content, removing header/footer elements,
 * replacing images with placeholders.
 */
function fetchPageData(string $url): ?array {
    $html = fetchUrlContent($url);
    if (!$html) return null;
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html);
    libxml_clear_errors();

    // Remove <header> and <footer>
    $xpath = new DOMXPath($doc);
    foreach (['header', 'footer'] as $tag) {
        foreach ($xpath->query('//' . $tag) as $node) {
            $node->parentNode->removeChild($node);
        }
    }

    // Replace <img> tags with <strong>Image</strong>
    $images = $doc->getElementsByTagName('img');
    for ($i = $images->length - 1; $i >= 0; $i--) {
        $img = $images->item($i);
        $placeholder = $doc->createElement('strong', 'Image');
        $img->parentNode->replaceChild($placeholder, $img);
    }

    // Page <title>
    $titleNode = $doc->getElementsByTagName('title')->item(0);
    $pageTitle = $titleNode ? $titleNode->nodeValue : '';

    // Meta description & meta title (or og:title fallback)
    $metaDesc = '';
    $metaTitle = '';
    foreach ($doc->getElementsByTagName('meta') as $meta) {
        $name = strtolower($meta->getAttribute('name'));
        $prop = strtolower($meta->getAttribute('property'));
        if ($name === 'description') {
            $metaDesc = $meta->getAttribute('content');
        }
        if ($name === 'title') {
            $metaTitle = $meta->getAttribute('content');
        }
        if (!$metaTitle && $prop === 'og:title') {
            $metaTitle = $meta->getAttribute('content');
        }
    }

    // Body inner HTML (cleaned)
    $bodyNode = $doc->getElementsByTagName('body')->item(0);
    $bodyHtml = '';
    if ($bodyNode) {
        foreach ($bodyNode->childNodes as $child) {
            $bodyHtml .= $doc->saveHTML($child);
        }
    }

    return [
        'url' => $url,
        'title' => trim($pageTitle),
        'description' => trim($metaDesc),
        'metaTitle' => trim($metaTitle),
        'content' => $bodyHtml
    ];
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sitemap = trim($_POST['sitemap_url'] ?? '');
    if (!$sitemap) {
        $error = 'Please enter a sitemap URL.';
    } else {
        $urls = getSitemapUrls($sitemap);
        if (empty($urls)) {
            $error = 'No URLs found or failed to load sitemap.';
        } else {
            // 1. Parse hostname from the sitemap URL
            $parsed = parse_url($sitemap);
            $host = $parsed['host'] ?? 'site';

            // 2. Generate a timestamp in YYYY‐MM‐DD_HH‐MM‐SS format
            //    (adjust timezone if needed, e.g. date_default_timezone_set('America/Edmonton'); )
            $timestamp = date('Y-m-d_H-i-s');

            // 3. Build the filename: <host>_<timestamp>.doc
            $filename = $host . '_' . $timestamp . '.doc';

            header('Content-Type: application/msword; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            echo "<html><head><meta charset='utf-8'></head><body>";
            foreach ($urls as $url) {
                $data = fetchPageData($url);
                if (!$data) continue;
                // Link to source page
                echo "<p><a href=\"" . htmlspecialchars($data['url']) . "\">" . htmlspecialchars($data['url']) . "</a></p>";
                echo "<section style='margin-bottom:40px;'>";
                echo "<h1>" . htmlspecialchars($data['title']) . "</h1>";
                echo "<h2>Meta Title: " . htmlspecialchars($data['metaTitle']) . "</h2>";
                echo "<h3>Meta Description:</h3><p>" . htmlspecialchars($data['description']) . "</p>";
                echo "<h3>Page Content:</h3>";
                echo $data['content'];
                echo "</section><hr />";
                flush();
                sleep(1);
            }
            echo "</body></html>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap Content Exporter – Bulk Page Copy Downloader</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Download page content, metadata, and headings from sitemap URLs into shareable reports using the BREN7 Sitemap Content Exporter.">
  <meta name="keywords" content="sitemap content exporter, bulk copy download, page text extractor, seo content audit, Morweb exporter">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Sitemap Content Exporter – Bulk Page Copy Downloader">
  <meta property="og:description" content="Capture on-page copy, metadata, and heading structure from sitemap URLs with this exporter by BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/sitemap-content-exporter.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Sitemap Content Exporter – Bulk Page Copy Downloader">
  <meta name="twitter:description" content="Export on-page copy for every sitemap URL with BREN7's content exporter.">
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
    /* (existing CSS remains unchanged) */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #F5F5F5;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
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
    .submit-btn:hover {
        background: #FFB800;
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 191, 165, 0.3);
    }
    .submit-btn:active {
        transform: translateY(-1px);
    }
    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: left 0.5s;
    }
    .submit-btn:hover::before {
        left: 100%;
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
    .features {
        margin-top: 32px;
        padding-top: 32px;
        border-top: 1px solid #F5F5F5;
    }
    .features h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333333;
        margin-bottom: 16px;
    }
    .feature-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #333333;
        font-size: 0.95rem;
    }
    .feature-item::before {
        content: '✓';
        background: #00BFA5;
        color: #FFFFFF;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
        flex-shrink: 0;
    }
    @media (max-width: 600px) {
        .container {
            padding: 32px 24px;
            margin: 20px;
        }
        .header h1 {
            font-size: 2rem;
        }
        .feature-list {
            grid-template-columns: 1fr;
        }
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
    .format-note {
        background: #F5F5F5;
        color: #333333;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 500;
        border-left: 4px solid #00BFA5;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
    }
    .format-note::before {
        content: '📄';
        font-size: 1.2rem;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Content Exporter</h1>
            <p>Convert your sitemap into a comprehensive Word document</p>
        </div>

        <div class="format-note">
            Exports a formatted Word document with full page content, meta data, and clickable links
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
                <span id="btnText">Export to Word Doc</span>
            </button>

            <div class="loading" id="loading">
                Processing sitemap and extracting content... This may take several minutes for large sites.
            </div>
        </form>

        <div class="features">
            <h3>Document Contents</h3>
            <div class="feature-list">
                <div class="feature-item">Clickable Source URLs</div>
                <div class="feature-item">Page Titles</div>
                <div class="feature-item">Meta Descriptions</div>
                <div class="feature-item">Meta Titles</div>
                <div class="feature-item">Full Page Content</div>
                <div class="feature-item">Formatted HTML</div>
                <div class="feature-item">Image Placeholders</div>
                <div class="feature-item">Clean Layout</div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('exportForm').addEventListener('submit', function() {
            const loading = document.getElementById('loading');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            
            loading.classList.add('show');
            submitBtn.disabled = true;
            btnText.textContent = '⏳ Processing...';
            submitBtn.style.opacity = '0.7';
        });

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
</body>
</html>
