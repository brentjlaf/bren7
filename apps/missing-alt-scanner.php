<?php
// Website Alt Tag Scanner - Single File Version
// Save as: scanner.php

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['api']) && $_POST['api'] === 'scan') {
    header('Content-Type: application/json');
    
    try {
        $sitemap_url = $_POST['sitemap_url'] ?? '';
        $max_pages = $_POST['max_pages'] ?? 50;
        
        if (empty($sitemap_url)) {
            throw new Exception('Sitemap URL is required');
        }

        if (!filter_var($sitemap_url, FILTER_VALIDATE_URL)) {
            throw new Exception('Invalid sitemap URL');
        }

        $scanner = new AltTagScanner();
        $results = $scanner->scanSitemap($sitemap_url, $max_pages);
        
        echo json_encode($results);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// Alt Tag Scanner Class
class AltTagScanner {
    private $user_agent = 'Mozilla/5.0 (compatible; AltTagScanner/1.0)';
    private $timeout = 30;
    private $max_redirects = 5;
    
    public function scanSitemap($sitemap_url, $max_pages = 50) {
        $max_pages = max(1, min(500, intval($max_pages)));
        
        $urls = $this->parseSitemap($sitemap_url);
        
        if (empty($urls)) {
            throw new Exception('No URLs found in sitemap or sitemap is invalid');
        }
        
        $urls = array_slice($urls, 0, $max_pages);
        
        $results = [
            'sitemap_url' => $sitemap_url,
            'pages_scanned' => 0,
            'total_images' => 0,
            'missing_alt_count' => 0,
            'missing_alt_images' => [],
            'scan_time' => date('Y-m-d H:i:s'),
            'errors' => []
        ];
        
        foreach ($urls as $url) {
            try {
                $page_results = $this->scanPage($url);
                $results['pages_scanned']++;
                $results['total_images'] += $page_results['total_images'];
                $results['missing_alt_count'] += count($page_results['missing_alt']);
                
                foreach ($page_results['missing_alt'] as $image) {
                    $image['page_url'] = $url;
                    $results['missing_alt_images'][] = $image;
                }
                
            } catch (Exception $e) {
                $results['errors'][] = [
                    'url' => $url,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
    
    private function parseSitemap($sitemap_url) {
        $content = $this->fetchUrl($sitemap_url);
        
        if (empty($content)) {
            throw new Exception('Could not fetch sitemap');
        }
        
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        
        if ($xml === false) {
            throw new Exception('Invalid XML in sitemap');
        }
        
        $urls = [];
        
        if (isset($xml->url)) {
            foreach ($xml->url as $url_entry) {
                if (isset($url_entry->loc)) {
                    $url = trim((string)$url_entry->loc);
                    if (!empty($url)) {
                        $urls[] = $url;
                    }
                }
            }
        }
        
        if (isset($xml->sitemap)) {
            foreach ($xml->sitemap as $sitemap_entry) {
                if (isset($sitemap_entry->loc)) {
                    try {
                        $sub_urls = $this->parseSitemap((string)$sitemap_entry->loc);
                        $urls = array_merge($urls, $sub_urls);
                        
                        if (count($urls) > 1000) {
                            break;
                        }
                    } catch (Exception $e) {
                        // Continue with other sitemaps
                    }
                }
            }
        }
        
        return array_unique(array_filter($urls));
    }
    
    private function scanPage($url) {
        $html = $this->fetchUrl($url);
        
        if (empty($html)) {
            throw new Exception("Could not fetch page: $url");
        }
        
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $success = $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
        
        if (!$success) {
            throw new Exception("Could not parse HTML for: $url");
        }
        
        $images = $dom->getElementsByTagName('img');
        $results = [
            'total_images' => $images->length,
            'missing_alt' => []
        ];
        
        foreach ($images as $img) {
            $alt = $img->getAttribute('alt');
            $src = $img->getAttribute('src');
            
            if (!$img->hasAttribute('alt') || trim($alt) === '') {
                $context = $this->getImageContext($img);
                
                $results['missing_alt'][] = [
                    'src' => $this->resolveUrl($src, $url),
                    'context' => $context,
                    'has_alt_attribute' => $img->hasAttribute('alt'),
                    'alt_content' => $alt
                ];
            }
        }
        
        return $results;
    }
    
    private function getImageContext($img_element) {
        $title = $img_element->getAttribute('title');
        if (!empty(trim($title))) {
            return trim($title);
        }
        
        $parent = $img_element->parentNode;
        if ($parent && $parent->nodeName === 'figure') {
            $figcaption = $parent->getElementsByTagName('figcaption');
            if ($figcaption->length > 0) {
                $caption_text = trim($figcaption->item(0)->textContent);
                if (!empty($caption_text)) {
                    return substr($caption_text, 0, 100);
                }
            }
        }
        
        $context = '';
        $attempts = 0;
        while ($parent && $attempts < 3) {
            if ($parent->nodeType === XML_ELEMENT_NODE) {
                $text = trim($parent->textContent);
                if (!empty($text) && strlen($text) < 200 && strlen($text) > 10) {
                    $context = substr($text, 0, 100);
                    break;
                }
            }
            $parent = $parent->parentNode;
            $attempts++;
        }
        
        return $context;
    }
    
    private function resolveUrl($relative_url, $base_url) {
        if (empty($relative_url)) {
            return '';
        }
        
        if (filter_var($relative_url, FILTER_VALIDATE_URL)) {
            return $relative_url;
        }
        
        $base = parse_url($base_url);
        if (!$base) {
            return $relative_url;
        }
        
        if (substr($relative_url, 0, 2) === '//') {
            return ($base['scheme'] ?? 'http') . ':' . $relative_url;
        }
        
        if (substr($relative_url, 0, 1) === '/') {
            return ($base['scheme'] ?? 'http') . '://' . ($base['host'] ?? '') . $relative_url;
        }
        
        $base_path = dirname($base['path'] ?? '/');
        if ($base_path === '.' || $base_path === '\\') {
            $base_path = '';
        }
        
        $full_path = $base_path . '/' . $relative_url;
        $full_path = preg_replace('#/+#', '/', $full_path);
        
        return ($base['scheme'] ?? 'http') . '://' . ($base['host'] ?? '') . $full_path;
    }
    
    private function fetchUrl($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid URL: $url");
        }
        
        $parsed = parse_url($url);
        if (!in_array($parsed['scheme'] ?? '', ['http', 'https'])) {
            throw new Exception("Only HTTP/HTTPS URLs are allowed");
        }
        
        if (function_exists('curl_init')) {
            return $this->fetchWithCurl($url);
        } else {
            return $this->fetchWithFileGetContents($url);
        }
    }
    
    private function fetchWithCurl($url) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => $this->max_redirects,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_ENCODING => '',
            CURLOPT_HTTPHEADER => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
                'Cache-Control: no-cache'
            ]
        ]);
        
        $content = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($content === false) {
            throw new Exception("cURL error: $error");
        }
        
        if ($http_code >= 400) {
            throw new Exception("HTTP error: $http_code");
        }
        
        return $content;
    }
    
    private function fetchWithFileGetContents($url) {
        $context = stream_context_create([
            'http' => [
                'timeout' => $this->timeout,
                'user_agent' => $this->user_agent,
                'follow_location' => true,
                'max_redirects' => $this->max_redirects
            ]
        ]);
        
        $content = @file_get_contents($url, false, $context);
        
        if ($content === false) {
            throw new Exception("Failed to fetch URL: $url");
        }
        
        return $content;
    }
}

// If not an API request, show the HTML interface
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Alt Tag Scanner – Image Accessibility Audit</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Scan sitemap URLs to pinpoint images missing alt attributes and export accessibility reports with the BREN7 Alt Tag Scanner.">
  <meta name="keywords" content="alt text scanner, accessibility audit, image SEO tool, sitemap accessibility, BREN7 compliance">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Website Alt Tag Scanner – Image Accessibility Audit">
  <meta property="og:description" content="Identify missing image alt attributes across your sitemap with the BREN7 Website Alt Tag Scanner.">
  <meta property="og:url" content="https://bren7.com/apps/missing-alt-scanner.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Website Alt Tag Scanner – Image Accessibility Audit">
  <meta name="twitter:description" content="Audit sitemap images for missing alt text using the BREN7 Alt Tag Scanner.">
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(15, 22, 38, 0.92);
            border-radius: 24px;
            box-shadow: 0 40px 70px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.45) 0%, rgba(118, 75, 162, 0.35) 100%);
            color: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .main-content {
            padding: 2rem;
        }

        .scan-form {
            background: rgba(17, 27, 45, 0.9);
            padding: 2rem;
            border-radius: 18px;
            margin-bottom: 2rem;
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid rgba(92, 204, 244, 0.25);
            border-radius: 12px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background: rgba(27, 39, 60, 0.75);
            color: rgba(255, 255, 255, 0.92);
        }

        .form-group input:focus {
            outline: none;
            border-color: rgba(102, 126, 234, 0.75);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .progress {
            margin: 2rem 0;
            display: none;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s ease;
        }

        .progress-text {
            text-align: center;
            margin-top: 0.5rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .results {
            display: none;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(17, 27, 45, 0.92);
            border: 1px solid rgba(92, 204, 244, 0.2);
            border-radius: 18px;
            padding: 1.5rem;
            text-align: center;
            transition: border-color 0.3s ease, transform 0.3s ease;
        }

        .stat-card:hover {
            border-color: rgba(102, 126, 234, 0.85);
            transform: translateY(-4px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-number.danger { color: #ef4444; }
        .stat-number.success { color: #10b981; }
        .stat-number.primary { color: #667eea; }

        .stat-label {
            color: rgba(255, 255, 255, 0.65);
            font-weight: 500;
        }

        .issues-section {
            background: rgba(56, 0, 12, 0.55);
            border: 1px solid rgba(239, 68, 68, 0.35);
            border-radius: 18px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .issues-section h3 {
            color: #dc2626;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .issues-section h3::before {
            content: "⚠️";
            margin-right: 0.5rem;
        }

        .page-issues {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background: rgba(17, 27, 45, 0.85);
            border-radius: 10px;
        }

        .issue-item {
            background: rgba(32, 8, 16, 0.85);
            border-left: 4px solid #ef4444;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border-radius: 0 12px 12px 12px;
            color: rgba(255, 255, 255, 0.82);
        }

        .issue-img {
            max-width: 100px;
            max-height: 60px;
            border-radius: 4px;
            margin-right: 0.75rem;
            float: left;
        }

        .issue-details {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .issue-src {
            font-family: monospace;
            background: rgba(12, 18, 32, 0.9);
            padding: 0.35rem 0.5rem;
            border-radius: 8px;
            word-break: break-all;
            color: rgba(255, 255, 255, 0.88);
        }

        .error-message {
            background: rgba(56, 6, 16, 0.75);
            border: 1px solid rgba(252, 165, 165, 0.4);
            color: #fca5a5;
            padding: 1rem;
            border-radius: 12px;
            margin: 1rem 0;
            display: none;
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-secondary {
            background: #6b7280;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .main-content {
                padding: 1rem;
            }

            .actions {
                flex-direction: column;
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
            <h1>🔍 Website Alt Tag Scanner</h1>
            <p>Scan your entire website for images missing alt text using sitemap.xml</p>
        </div>

        <div class="main-content">
            <form class="scan-form" id="scanForm">
                <div class="form-group">
                    <label for="sitemapUrl">Website Sitemap URL</label>
                    <input 
                        type="url" 
                        id="sitemapUrl" 
                        name="sitemap_url" 
                        placeholder="https://example.com/sitemap.xml"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="maxPages">Maximum Pages to Scan (Optional)</label>
                    <input 
                        type="number" 
                        id="maxPages" 
                        name="max_pages" 
                        placeholder="50"
                        min="1"
                        max="500"
                    >
                </div>

                <button type="submit" class="btn" id="scanBtn">
                    🚀 Start Scanning
                </button>
            </form>

            <div class="error-message" id="errorMessage"></div>

            <div class="progress" id="progress">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="progress-text" id="progressText">Initializing scan...</div>
            </div>

            <div class="results" id="results">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number primary" id="totalPages">0</div>
                        <div class="stat-label">Pages Scanned</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number primary" id="totalImages">0</div>
                        <div class="stat-label">Total Images</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number danger" id="missingAlt">0</div>
                        <div class="stat-label">Missing Alt Tags</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number success" id="hasAlt">0</div>
                        <div class="stat-label">Has Alt Tags</div>
                    </div>
                </div>

                <div class="issues-section" id="issuesSection">
                    <h3>Images Missing Alt Tags</h3>
                    <div id="issuesList"></div>
                </div>

                <div class="actions">
                    <button class="btn" onclick="exportReport()">📄 Export Report</button>
                    <button class="btn btn-secondary" onclick="resetScan()">🔄 New Scan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let scanData = {};

        document.getElementById('scanForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const sitemapUrl = formData.get('sitemap_url');
            const maxPages = formData.get('max_pages') || 50;

            if (!sitemapUrl) {
                showError('Please enter a sitemap URL');
                return;
            }

            try {
                new URL(sitemapUrl);
            } catch {
                showError('Please enter a valid URL');
                return;
            }

            startScan(sitemapUrl, maxPages);
        });

        async function startScan(sitemapUrl, maxPages) {
            const scanBtn = document.getElementById('scanBtn');
            const progress = document.getElementById('progress');
            const results = document.getElementById('results');

            hideError();
            scanBtn.disabled = true;
            scanBtn.textContent = '🔄 Scanning...';
            progress.style.display = 'block';
            results.style.display = 'none';

            try {
                updateProgress(10, 'Fetching sitemap...');

                const formData = new FormData();
                formData.append('api', 'scan');
                formData.append('sitemap_url', sitemapUrl);
                formData.append('max_pages', maxPages);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }

                updateProgress(50, 'Processing pages...');

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                scanData = data;
                updateProgress(100, 'Scan completed!');

                setTimeout(() => {
                    displayResults(data);
                }, 500);

            } catch (error) {
                console.error('Scan error:', error);
                showError('Error during scan: ' + error.message);
                resetScanButton();
                document.getElementById('progress').style.display = 'none';
            }
        }

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        function hideError() {
            document.getElementById('errorMessage').style.display = 'none';
        }

        function updateProgress(percentage, text) {
            document.getElementById('progressFill').style.width = percentage + '%';
            document.getElementById('progressText').textContent = text;
        }

        function displayResults(data) {
            document.getElementById('progress').style.display = 'none';
            document.getElementById('results').style.display = 'block';

            document.getElementById('totalPages').textContent = data.pages_scanned || 0;
            document.getElementById('totalImages').textContent = data.total_images || 0;
            document.getElementById('missingAlt').textContent = data.missing_alt_count || 0;
            document.getElementById('hasAlt').textContent = (data.total_images || 0) - (data.missing_alt_count || 0);

            const issuesList = document.getElementById('issuesList');
            issuesList.innerHTML = '';

            if (!data.missing_alt_images || data.missing_alt_images.length === 0) {
                issuesList.innerHTML = '<p style="color: #10b981; text-align: center; padding: 2rem;">🎉 Great! No missing alt tags found!</p>';
            } else {
                const pageGroups = {};
                data.missing_alt_images.forEach(image => {
                    if (!pageGroups[image.page_url]) {
                        pageGroups[image.page_url] = [];
                    }
                    pageGroups[image.page_url].push(image);
                });

                Object.keys(pageGroups).forEach(pageUrl => {
                    const pageDiv = document.createElement('div');
                    pageDiv.className = 'page-issues';
                    
                    const pageTitle = document.createElement('div');
                    pageTitle.className = 'page-title';
                    pageTitle.textContent = `📄 ${pageUrl} (${pageGroups[pageUrl].length} issues)`;
                    pageDiv.appendChild(pageTitle);

                    pageGroups[pageUrl].forEach(image => {
                        const issueDiv = document.createElement('div');
                        issueDiv.className = 'issue-item';
                        
                        issueDiv.innerHTML = `
                            ${image.src ? `<img src="${escapeHtml(image.src)}" alt="Missing alt text" class="issue-img" onerror="this.style.display='none'">` : ''}
                            <div class="issue-details">
                                <strong>Image source:</strong><br>
                                <code class="issue-src">${escapeHtml(image.src || 'No src attribute')}</code>
                                ${image.context ? `<br><br><strong>Context:</strong> ${escapeHtml(image.context)}` : ''}
                            </div>
                            <div style="clear: both;"></div>
                        `;
                        
                        pageDiv.appendChild(issueDiv);
                    });

                    issuesList.appendChild(pageDiv);
                });
            }

            resetScanButton();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function resetScanButton() {
            const scanBtn = document.getElementById('scanBtn');
            scanBtn.disabled = false;
            scanBtn.textContent = '🚀 Start Scanning';
        }

        function exportReport() {
            if (!scanData.missing_alt_images) {
                showError('No scan data to export');
                return;
            }

            let csv = 'Page URL,Image Source,Context\n';
            scanData.missing_alt_images.forEach(image => {
                const pageUrl = (image.page_url || '').replace(/"/g, '""');
                const src = (image.src || '').replace(/"/g, '""');
                const context = (image.context || '').replace(/"/g, '""');
                csv += `"${pageUrl}","${src}","${context}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `alt-tag-report-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        }

        function resetScan() {
            document.getElementById('results').style.display = 'none';
            document.getElementById('progress').style.display = 'none';
            document.getElementById('scanForm').reset();
            hideError();
            scanData = {};
        }

        document.getElementById('sitemapUrl').addEventListener('blur', (e) => {
            let url = e.target.value.trim();
            if (url && !url.includes('sitemap') && !url.endsWith('.xml')) {
                if (!url.endsWith('/')) url += '/';
                e.target.value = url + 'sitemap.xml';
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

  <script src="/js/app-shell.js" defer></script>
</body>
</html>