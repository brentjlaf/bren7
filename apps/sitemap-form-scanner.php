<?php
// mwformscan.php (Sitemap version scanning for non-search forms)
// Form scanner: input sitemap URL, fetch all pages, list each page's non-search form counts in a table,
// and display a summary of total non-search forms at the bottom.

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

    // Scan each URL for non-search <form> tags
    $results = [];
    $totalForms = 0;
    foreach ($urls as $pageUrl) {
        $ch = curl_init($pageUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'MWFormScan/1.2',
            CURLOPT_TIMEOUT        => 10,
        ]);
        $html = curl_exec($ch);
        curl_close($ch);
        if ($html === false) {
            continue;
        }

        // Parse for <form> occurrences
        libxml_use_internal_errors(true);
        $dom   = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $forms = $xpath->query('//form');

        $count = 0;
        foreach ($forms as $form) {
            $isSearch = false;
            // Check role="search"
            if (strtolower($form->getAttribute('role')) === 'search') {
                $isSearch = true;
            }
            // Check action URL for "search"
            $action = $form->getAttribute('action');
            if (!$isSearch && $action && stripos($action, 'search') !== false) {
                $isSearch = true;
            }
            // Check input fields for search-type inputs
            if (!$isSearch) {
                foreach ($form->getElementsByTagName('input') as $input) {
                    $type = strtolower($input->getAttribute('type'));
                    $name = strtolower($input->getAttribute('name'));
                    if ($type === 'search' || in_array($name, ['s', 'q', 'search', 'query'])) {
                        $isSearch = true;
                        break;
                    }
                }
            }
            if (!$isSearch) {
                $count++;
            }
        }

        $results[] = ['page' => $pageUrl, 'count' => $count];
        $totalForms += $count;
    }

    // Prepare summary
    $summary = ['Total Non-Search Forms Found' => $totalForms];
    echo json_encode(['results' => $results, 'summary' => $summary]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MW Form Scanner – Form Inventory & Action Audit</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Scan sitemap URLs to catalog forms, capture endpoints, and verify submission methods with the BREN7 MW Form Scanner.">
  <meta name="keywords" content="form scanner, form inventory tool, submission endpoint audit, sitemap form analysis, BREN7 form checker">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="MW Form Scanner – Form Inventory & Action Audit">
  <meta property="og:description" content="Discover forms, actions, and submission types across your sitemap with the MW Form Scanner from BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/sitemap-form-scanner.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="MW Form Scanner – Form Inventory & Action Audit">
  <meta name="twitter:description" content="Catalog forms and submission endpoints across pages with BREN7's MW Form Scanner.">
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

    /* BREN7 nav overrides (unused here) */
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

/* Layout */
.container {
    max-width: 1200px;
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

/* Links */
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
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-code"></i> MW Form Scanner</h1>
            <p class="subtitle">Identify non-search &lt;form&gt; tags across your site</p>
        </div>

        <div class="card">
            <h2><i class="fas fa-info-circle"></i> About This Tool</h2>
            <p>This scanner analyzes your site's sitemap to identify all &lt;form&gt; tags that are <strong>not</strong> used for search. It provides a detailed report of how many non-search forms exist on each page, plus a summary of the total found.</p>
        </div>

        <div class="card instructions">
            <h2><i class="fas fa-list-ol"></i> How It Works</h2>
            <ol>
                <li>Enter your site's full <code>sitemap.xml</code> URL (e.g., <code>https://example.com/sitemap.xml</code>).</li>
                <li>Click <strong>Start Scan</strong> to begin fetching each page.</li>
                <li>The tool will count every &lt;form&gt; that isn’t designated as a "search" form.</li>
                <li>Review the detailed results showing page URLs and the number of non-search forms per page.</li>
                <li>See the summary section for total non-search forms found across the entire site.</li>
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

        <div class="results-container" id="results-container" style="display: none;">
            <div class="card">
                <h2><i class="fas fa-table"></i> Detailed Results</h2>
                <div class="table-wrapper">
                    <table id="results-table">
                        <thead>
                            <tr>
                                <th>Page URL</th>
                                <th>Form Count</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="card summary-section" id="summary-section">
                <h2><i class="fas fa-chart-bar"></i> Usage Summary</h2>
                <div class="stats-grid" id="stats-grid"></div>
                <div class="table-wrapper">
                    <table id="summary-table" class="summary-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Count</th>
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
        $('#status').removeClass('error success').addClass('loading').html('<div class="spinner"></div> Scanning sitemap… this may take a while...').show();
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
            if (!rows.length) {
                $('#status').removeClass('loading error').addClass('success').text('Scan completed — no non-search forms were found.');
                return;
            }

            // Calculate summary stats
            var totalPages = rows.length;
            var totalForms = Object.values(data.summary)[0] || 0;

            // Show success state
            $('#status').removeClass('loading error').addClass('success')
                        .text('Scan completed! ' + totalForms + ' non-search form' + (totalForms !== 1 ? 's' : '') + ' found on ' + totalPages + ' page' + (totalPages !== 1 ? 's' : '') + '.');

            // Populate detailed results
            var $tbody = $('#results-table tbody');
            $tbody.empty();
            rows.forEach(function(r) {
                $tbody.append(
                    $('<tr>').append(
                        $('<td>').append($('<a>')
                            .addClass('page-link')
                            .attr('href', r.page)
                            .attr('target', '_blank')
                            .html('<i class="fas fa-external-link-alt" style="font-size: 0.8em; opacity: 0.7;"></i> ' + r.page)
                        ),
                        $('<td>').html('<span class="count-badge">' + r.count + '</span>')
                    )
                );
            });

            // Populate stats cards
            var $statsGrid = $('#stats-grid');
            $statsGrid.html(
                '<div class="stat-card"><span class="stat-number">' + totalPages + '</span><div class="stat-label">Pages Scanned</div></div>' +
                '<div class="stat-card"><span class="stat-number">' + totalForms + '</span><div class="stat-label">Total Non-Search Forms</div></div>'
            );

            // Populate summary table
            var $sumTbody = $('#summary-table tbody');
            $sumTbody.empty();
            Object.keys(data.summary).forEach(function(desc) {
                $sumTbody.append(
                    $('<tr>').append(
                        $('<td>').text(desc),
                        $('<td>').text(data.summary[desc])
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
