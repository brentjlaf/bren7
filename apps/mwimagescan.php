<?php
// mwimagescan.php (Sitemap version with summary)
// Image scanner: input a sitemap URL, fetch all pages, list each page's images and counts in a table,
// and display a summary of total image usage at the bottom.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    // Validate sitemap URL
    if (empty($_POST['sitemap']) || !filter_var($_POST['sitemap'], FILTER_VALIDATE_URL)) {
        echo json_encode(['error' => 'Invalid or missing sitemap URL']);
        exit;
    }
    $sitemapUrl = $_POST['sitemap'];

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

    // Scan each URL for images
    $results = [];
    $totalPages = count($urls);
    $processedPages = 0;
    
    foreach ($urls as $pageUrl) {
        $ch = curl_init($pageUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'MWImageScan/1.0',
            CURLOPT_TIMEOUT        => 15,
        ]);
        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $processedPages++;
        
        if ($html === false || $httpCode >= 400) {
            continue;
        }

        // Parse for image elements
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        // Find all img tags (including those inside picture elements)
        $imgNodes = $xpath->query('//img');
        
        $imageData = [];
        foreach ($imgNodes as $imgNode) {
            $src = trim($imgNode->getAttribute('src'));
            $alt = trim($imgNode->getAttribute('alt'));
            $title = trim($imgNode->getAttribute('title'));
            $class = trim($imgNode->getAttribute('class'));
            $width = trim($imgNode->getAttribute('width'));
            $height = trim($imgNode->getAttribute('height'));
            
            // Check if this img is inside a picture element
            $parentPicture = null;
            $currentNode = $imgNode->parentNode;
            while ($currentNode && $currentNode->nodeName !== 'html') {
                if ($currentNode->nodeName === 'picture') {
                    $parentPicture = $currentNode;
                    break;
                }
                $currentNode = $currentNode->parentNode;
            }
            
            $isInPicture = $parentPicture !== null;
            $pictureInfo = '';
            
            if ($isInPicture) {
                // Get source elements from the picture tag
                $sourceNodes = $xpath->query('.//source', $parentPicture);
                $sources = [];
                foreach ($sourceNodes as $sourceNode) {
                    $srcset = trim($sourceNode->getAttribute('srcset'));
                    $media = trim($sourceNode->getAttribute('media'));
                    $type = trim($sourceNode->getAttribute('type'));
                    if ($srcset) {
                        $sourceInfo = $srcset;
                        if ($media) $sourceInfo .= ' (' . $media . ')';
                        if ($type) $sourceInfo .= ' [' . $type . ']';
                        $sources[] = $sourceInfo;
                    }
                }
                if (!empty($sources)) {
                    $pictureInfo = 'Picture sources: ' . implode('; ', array_slice($sources, 0, 2));
                    if (count($sources) > 2) {
                        $pictureInfo .= ' +' . (count($sources) - 2) . ' more';
                    }
                }
            }
            
            if ($src !== '') {
                // Skip base64 encoded images (data URLs)
                if (strpos($src, 'data:') === 0) {
                    continue;
                }
                
                // Convert relative URLs to absolute
                if (strpos($src, 'http') !== 0) {
                    $parsedUrl = parse_url($pageUrl);
                    $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                    if (substr($src, 0, 1) === '/') {
                        $src = $baseUrl . $src;
                    } else {
                        $src = $baseUrl . '/' . ltrim($src, '/');
                    }
                }
                
                // Get file extension and size info
                $pathInfo = pathinfo(parse_url($src, PHP_URL_PATH));
                $extension = isset($pathInfo['extension']) ? strtolower($pathInfo['extension']) : 'unknown';
                $filename = isset($pathInfo['basename']) ? $pathInfo['basename'] : 'unknown';
                
                $imageKey = $src;
                if (!isset($imageData[$imageKey])) {
                    $imageData[$imageKey] = [
                        'src' => $src,
                        'filename' => $filename,
                        'extension' => $extension,
                        'alt' => $alt,
                        'title' => $title,
                        'class' => $class,
                        'width' => $width,
                        'height' => $height,
                        'is_in_picture' => $isInPicture,
                        'picture_info' => $pictureInfo,
                        'count' => 0
                    ];
                }
                $imageData[$imageKey]['count']++;
            }
        }

        // Add results for this page
        foreach ($imageData as $data) {
            $results[] = [
                'page' => $pageUrl,
                'src' => $data['src'],
                'filename' => $data['filename'],
                'extension' => $data['extension'],
                'alt' => $data['alt'],
                'title' => $data['title'],
                'class' => $data['class'],
                'width' => $data['width'],
                'height' => $data['height'],
                'is_in_picture' => $data['is_in_picture'],
                'picture_info' => $data['picture_info'],
                'count' => $data['count']
            ];
        }
    }

    // Compute summary counts across all pages
    $summary = [];
    $extensionSummary = [];
    $totalImages = 0;
    
    foreach ($results as $row) {
        $src = $row['src'];
        $ext = $row['extension'];
        $count = $row['count'];
        
        // Summary by image URL
        if (!isset($summary[$src])) {
            $summary[$src] = [
                'filename' => $row['filename'],
                'extension' => $ext,
                'alt' => $row['alt'],
                'total_count' => 0,
                'pages' => []
            ];
        }
        $summary[$src]['total_count'] += $count;
        $summary[$src]['pages'][] = $row['page'];
        
        // Summary by file extension
        if (!isset($extensionSummary[$ext])) {
            $extensionSummary[$ext] = 0;
        }
        $extensionSummary[$ext] += $count;
        
        $totalImages += $count;
    }

    // Return JSON with detailed results and summaries
    echo json_encode([
        'results' => $results,
        'summary' => $summary,
        'extensionSummary' => $extensionSummary,
        'totalImages' => $totalImages,
        'totalPages' => $processedPages,
        'totalUniqueImages' => count($summary)
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MW Image Scanner</title>
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

    /* Navigation overrides */
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
}
input[type="url"] {
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
input[type="url"]:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}
input[type="url"]::placeholder {
    color: rgba(255, 255, 255, 0.6);
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
}
.page-link:hover {
    color: var(--primary-blue-dark);
    text-decoration: underline;
}
.image-link {
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
.image-link:hover {
    color: var(--primary-blue-dark);
    text-decoration: underline;
}
.filename {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    background: var(--fade-primary-blue);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    color: var(--primary-blue);
    font-weight: 500;
}
.extension-badge {
    background: var(--accent-green);
    color: var(--white);
    padding: 0.2rem 0.4rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    min-width: 2rem;
    text-align: center;
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

/* Image preview */
.image-preview {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid var(--medium-gray);
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
    .btn {
        justify-content: center;
    }
    .image-link {
        max-width: 200px;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-images"></i> MW Image Scanner</h1>
            <p class="subtitle">Discover all images used across your site</p>
        </div>

        <div class="card">
            <h2><i class="fas fa-info-circle"></i> About This Tool</h2>
            <p>This scanner analyzes your site's sitemap to identify all images being used across your pages. It finds <code>&lt;img&gt;</code> tags (including those inside <code>&lt;picture&gt;</code> elements), excluding base64 encoded images, providing clean reporting on actual image files used on your site.</p>
        </div>

        <div class="card instructions">
            <h2><i class="fas fa-list-ol"></i> How It Works</h2>
            <ol>
                <li>Enter your site's complete sitemap URL (e.g., <code>https://example.com/sitemap.xml</code>)</li>
                <li>Click <strong>Start Scan</strong> to begin analyzing all pages</li>
                <li>Review detailed results showing every <code>&lt;img&gt;</code> tag found (including those in <code>&lt;picture&gt;</code> elements)</li>
                <li>Check summary statistics for image usage patterns and file type distribution</li>
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
                            Start Scan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="status" class="status"></div>

        <div class="results-container" id="results-container">
            <div class="card">
                <h2><i class="fas fa-table"></i> Detailed Results</h2>
                <div class="table-wrapper">
                    <table id="results-table">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Page URL</th>
                                <th>Image Filename</th>
                                <th>Type</th>
                                <th>Alt Text</th>
                                <th>Picture Element</th>
                                <th>Dimensions</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="card summary-section" id="summary-section">
                <h2><i class="fas fa-chart-bar"></i> Image Usage Summary</h2>
                <div class="stats-grid" id="stats-grid"></div>
                
                <h3 style="color: white; margin: 2rem 0 1rem 0;"><i class="fas fa-file-image"></i> File Type Distribution</h3>
                <div class="table-wrapper">
                    <table id="extension-table" class="summary-table">
                        <thead>
                            <tr>
                                <th>File Type</th>
                                <th>Total Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <h3 style="color: white; margin: 2rem 0 1rem 0;"><i class="fas fa-images"></i> Most Used Images</h3>
                <div class="table-wrapper">
                    <table id="summary-table" class="summary-table">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Image Filename</th>
                                <th>Type</th>
                                <th>Total Occurrences</th>
                                <th>Pages Using</th>
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
        var url = $('#sitemap-url').val().trim();
        if (!url) return;

        // Show loading state
        $('#status').removeClass('error success').addClass('loading').html('<div class="spinner"></div> Scanning sitemap for images… this may take a while...').show();
        $('#scan-btn').prop('disabled', true).html('<div class="spinner"></div> Scanning...');
        $('#results-container').hide();

        $.post('<?php echo $_SERVER["PHP_SELF"]; ?>', { sitemap: url })
         .done(function(data) {
            $('#scan-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Start Scan');
            
            if (data.error) {
                $('#status').removeClass('loading success').addClass('error').text(data.error);
                return;
            }

            var rows = data.results || [];
            var totalImages = data.totalImages || 0;
            var totalPages = data.totalPages || 0;
            var totalUniqueImages = data.totalUniqueImages || 0;
            
            if (!rows.length) {
                $('#status').removeClass('loading error').addClass('success').text('Scan completed successfully, but no images were found.');
                return;
            }

            $('#status').removeClass('loading error').addClass('success').text('Scan completed! Found ' + totalImages + ' images across ' + totalPages + ' pages.');

            // Populate detailed results
            var $tbody = $('#results-table tbody');
            $tbody.empty();
            rows.forEach(function(r) {
                var dimensions = '';
                if (r.width && r.height) {
                    dimensions = r.width + ' × ' + r.height;
                } else if (r.width) {
                    dimensions = 'W: ' + r.width;
                } else if (r.height) {
                    dimensions = 'H: ' + r.height;
                }
                
                var altText = r.alt || '(no alt text)';
                if (altText.length > 50) {
                    altText = altText.substring(0, 50) + '...';
                }
                
                var pictureStatus = '';
                if (r.is_in_picture) {
                    pictureStatus = '<span class="extension-badge" style="background: var(--secondary-blue);">PICTURE</span>';
                    if (r.picture_info) {
                        pictureStatus += '<br><small style="color: var(--neutral-gray); font-size: 0.75rem;">' + r.picture_info + '</small>';
                    }
                } else {
                    pictureStatus = '<span style="color: var(--neutral-gray); font-size: 0.85rem;">Standard IMG</span>';
                }

                $tbody.append(
                    $('<tr>').append(
                        $('<td>').html('<img src="' + r.src + '" class="image-preview" onerror="this.style.display=\'none\'" alt="Preview">'),
                        $('<td>').append($('<a>').addClass('page-link').attr('href', r.page).attr('target', '_blank').html('<i class="fas fa-external-link-alt" style="font-size: 0.8em; opacity: 0.7;"></i> ' + (r.page.length > 50 ? r.page.substring(0, 50) + '...' : r.page))),
                        $('<td>').html('<a href="' + r.src + '" target="_blank" class="image-link"><span class="filename">' + r.filename + '</span></a>'),
                        $('<td>').html('<span class="extension-badge">' + r.extension + '</span>'),
                        $('<td>').text(altText),
                        $('<td>').html(pictureStatus),
                        $('<td>').text(dimensions),
                        $('<td>').html('<span class="count-badge">' + r.count + '</span>')
                    )
                );
            });

            // Create stats cards
            var $statsGrid = $('#stats-grid');
            $statsGrid.html(
                '<div class="stat-card"><span class="stat-number">' + totalUniqueImages + '</span><div class="stat-label">Unique Images</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + totalImages + '</span><div class="stat-label">Total Occurrences</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + totalPages + '</span><div class="stat-label">Pages Scanned</div></div>'
            );

            // Populate file type distribution table
            var extensionData = data.extensionSummary || {};
            var $extTbody = $('#extension-table tbody');
            $extTbody.empty();
            
            // Sort extensions by count (descending)
            var sortedExtensions = Object.keys(extensionData).sort((a, b) => extensionData[b] - extensionData[a]);
            
            sortedExtensions.forEach(function(ext) {
                var count = extensionData[ext];
                var percentage = ((count / totalImages) * 100).toFixed(1);
                $extTbody.append(
                    $('<tr>').append(
                        $('<td>').html('<span class="extension-badge">' + ext.toUpperCase() + '</span>'),
                        $('<td>').html('<span class="count-badge">' + count + '</span>'),
                        $('<td>').text(percentage + '%')
                    )
                );
            });

            // Populate most used images summary table
            var summaryData = data.summary || {};
            var $sumTbody = $('#summary-table tbody');
            $sumTbody.empty();
            
            // Sort images by total count (descending) and take top 20
            var sortedImages = Object.keys(summaryData)
                .sort((a, b) => summaryData[b].total_count - summaryData[a].total_count)
                .slice(0, 20);
            
            sortedImages.forEach(function(src) {
                var imageData = summaryData[src];
                var uniquePages = [...new Set(imageData.pages)].length;
                
                $sumTbody.append(
                    $('<tr>').append(
                        $('<td>').html('<img src="' + src + '" class="image-preview" onerror="this.style.display=\'none\'" alt="Preview">'),
                        $('<td>').html('<a href="' + src + '" target="_blank" class="image-link"><span class="filename">' + imageData.filename + '</span></a>'),
                        $('<td>').html('<span class="extension-badge">' + imageData.extension.toUpperCase() + '</span>'),
                        $('<td>').html('<span class="count-badge">' + imageData.total_count + '</span>'),
                        $('<td>').text(uniquePages + ' page' + (uniquePages !== 1 ? 's' : ''))
                    )
                );
            });

            $('#results-container').show();
        })
         .fail(function(xhr, status, error) {
            $('#scan-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Start Scan');
            $('#status').removeClass('loading success').addClass('error').text('Request failed: ' + error);
        });
    });
    </script>
</body>
</html>