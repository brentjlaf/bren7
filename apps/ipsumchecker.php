<?php
// lorum_ipsum_checker.php (Sitemap-based Lorem Ipsum detector)

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

    $results = [];
    // Scan each URL for "lorem ipsum"
    foreach ($urls as $pageUrl) {
        $ch = curl_init($pageUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'LoreumIpsumChecker/1.0',
            CURLOPT_TIMEOUT        => 10,
        ]);
        $html = curl_exec($ch);
        curl_close($ch);
        if ($html === false) {
            continue;
        }

        // Count occurrences of "lorem ipsum" (case-insensitive)
        preg_match_all('/lorem ipsum/i', $html, $matches);
        $count = count($matches[0]);

        if ($count > 0) {
            $results[] = ['page' => $pageUrl, 'count' => $count];
        }
    }

    // Compute summary
    $totalOccurrences = 0;
    $pagesWith = count($results);
    foreach ($results as $row) {
        $totalOccurrences += $row['count'];
    }
    $pagesScanned = count($urls);

    echo json_encode([
        'results' => $results,
        'summary' => [
            'pages_with_lorem' => $pagesWith,
            'total_occurrences' => $totalOccurrences,
            'pages_scanned' => $pagesScanned
        ]
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lorem Ipsum Checker</title>
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

    /* Morweb nav overrides */
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
.template-name {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    background: var(--fade-primary-blue);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    color: var(--primary-blue);
    font-weight: 500;
}
.count-badge {
    background: var(--accent-green);
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
.summary-section h2 {
    color: var(--white);
}
/* Summary Section – tables with dark text */
.summary-table th {
    background: rgba(229, 231, 235, 0.3); /* light neutral background */
    color: var(--dark-gray);               /* dark text */
    border-bottom-color: var(--medium-gray);
}

.summary-table td {
    color: var(--dark-gray);               /* dark text */
    border-bottom-color: var(--medium-gray);
}

.summary-table tbody tr:nth-child(even) {
    background: var(--light-gray);          /* subtle striped rows */
}

.summary-table tbody tr:hover {
    background: var(--fade-secondary-blue); /* hover highlight */
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
	
	<!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1RGGXKCNB6"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-1RGGXKCNB6');
  </script>
	
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-italic"></i> Lorem Ipsum Checker</h1>
            <p class="subtitle">Scan your site for any Lorem Ipsum placeholder text</p>
        </div>

        <div class="card form-section">
            <h2><i class="fas fa-search"></i> Check Configuration</h2>
            <form id="check-form">
                <div class="form-group">
                    <label for="sitemap-url">Sitemap URL</label>
                    <div class="input-wrapper">
                        <input type="url" id="sitemap-url" name="sitemap" placeholder="https://example.com/sitemap.xml" required>
                        <button type="submit" class="btn btn-primary" id="check-btn">
                            <i class="fas fa-play"></i>
                            Start Check
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="status" class="status"></div>

        <div class="results-container" id="results-container" style="display:none;">
            <div class="card">
                <h2><i class="fas fa-table"></i> Detection Results</h2>
                <div class="table-wrapper">
                    <table id="results-table">
                        <thead>
                            <tr>
                                <th>Page URL</th>
                                <th>Lorem Ipsum Count</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="card summary-section">
                <h2><i class="fas fa-chart-bar"></i> Summary</h2>
                <div class="stats-grid" id="stats-grid"></div>
                <div class="table-wrapper">
                    <table id="summary-table" class="summary-table">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Pages Scanned</td><td id="metric-pages"></td></tr>
                            <tr><td>Pages with Lorem Ipsum</td><td id="metric-pages-with"></td></tr>
                            <tr><td>Total Occurrences</td><td id="metric-occurrences"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    $('#check-form').on('submit', function(e) {
        e.preventDefault();
        var url = $('#sitemap-url').val().trim();
        if (!url) return;

        $('#status').removeClass('error success').addClass('loading').html('<div class="spinner"></div> Scanning… this may take a while...').show();
        $('#check-btn').prop('disabled', true).html('<div class="spinner"></div> Checking...');
        $('#results-container').hide();

        $.post('<?php echo $_SERVER["PHP_SELF"]; ?>', { sitemap: url })
         .done(function(data) {
            $('#check-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Start Check');
            if (data.error) {
                $('#status').removeClass('loading success').addClass('error').text(data.error);
                return;
            }

            var rows = data.results || [];
            $('#status').removeClass('loading error').addClass('success').text('Check completed! ' + rows.length + ' pages contained Lorem Ipsum.');

            var $tbody = $('#results-table tbody');
            $tbody.empty();
            rows.forEach(function(r) {
                $tbody.append(
                    $('<tr>').append(
                        $('<td>').append($('<a>').addClass('page-link').attr('href', r.page).attr('target', '_blank').html('<i class="fas fa-external-link-alt" style="opacity:0.7;"></i> ' + r.page)),
                        $('<td>').text(r.count)
                    )
                );
            });

            var summary = data.summary || {};
            $('#metric-pages').text(summary.pages_scanned);
            $('#metric-pages-with').text(summary.pages_with_lorem);
            $('#metric-occurrences').text(summary.total_occurrences);

            $('#results-container').show();
        })
         .fail(function(xhr, status, error) {
            $('#check-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Start Check');
            $('#status').removeClass('loading success').addClass('error').text('Request failed: ' + error);
        });
    });
    </script>
</body>
</html>