<?php
// sitemap-seo-audit.php - Sitemap-based SEO auditing tool styled like MW Form Scanner

// Show all PHP errors (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!class_exists('DOMDocument')) {
    die('Error: PHP DOM extension is not installed.');
}

/**
 * Fetch remote content via file_get_contents or cURL
 */
function fetchUrl($url) {
    if (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create(['http' => ['header' => "User-Agent: SEO Auditor/1.0"]]);
        return @file_get_contents($url, false, $ctx);
    }
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SEO Auditor/1.0');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    die('Error: Cannot fetch remote URL. Enable allow_url_fopen or cURL.');
}

/**
 * Get single node value/status via XPath
 */
function getSingle($xpath, $query, $attr = null) {
    $nodes = $xpath->query($query);
    if ($nodes->length === 0) {
        return ['value' => 'Missing', 'status' => false];
    }
    $node = $nodes->item(0);
    $val = $attr ? $node->getAttribute($attr) : trim($node->textContent);
    if ($val === '') {
        return ['value' => 'Missing', 'status' => false];
    }
    return ['value' => $val, 'status' => true];
}

$all_results = [];
$error = '';
$sitemap_url = '';

if (!empty($_GET['sitemap_url'])) {
    $sitemap_url = filter_var($_GET['sitemap_url'], FILTER_SANITIZE_URL);
    if (!filter_var($sitemap_url, FILTER_VALIDATE_URL)) {
        $error = 'Invalid sitemap URL format.';
    } else {
        $xmlContent = fetchUrl($sitemap_url);
        if (!$xmlContent) {
            $error = 'Failed to fetch sitemap.';
        } else {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlContent);
            libxml_clear_errors();
            if (!$xml || !isset($xml->url)) {
                $error = 'Invalid sitemap XML.';
            } else {
                $urls = [];
                foreach ($xml->url as $u) {
                    $loc = (string)$u->loc;
                    if (filter_var($loc, FILTER_VALIDATE_URL)) {
                        $urls[] = $loc;
                    }
                }
                foreach ($urls as $page_url) {
                    $results = [];
                    $html = fetchUrl($page_url);
                    if (!$html) {
                        $labels = [
                            'Page Title','Meta Description','First H1','H1 Count',
                            'Images Missing Alt','Meta Robots','Canonical','JSON-LD',
                            'Microdata','OG Title','Twitter Card','Viewport','Favicon','HTML Lang'
                        ];
                        foreach ($labels as $lab) {
                            $results[] = ['label' => $lab, 'value' => 'Error', 'status' => false];
                        }
                    } else {
                        libxml_use_internal_errors(true);
                        $doc = new DOMDocument();
                        @$doc->loadHTML($html);
                        libxml_clear_errors();
                        $xpath = new DOMXPath($doc);

                        // Page Title
                        $titles = $doc->getElementsByTagName('title');
                        $title = $titles->length ? trim($titles->item(0)->textContent) : '';
                        $results[] = ['label' => 'Page Title', 'value' => $title ?: 'Missing', 'status' => !empty($title)];

                        // Meta Description
                        $md = getSingle($xpath, "//meta[@name='description']", "content");
                        $results[] = ['label' => 'Meta Description', 'value' => $md['value'], 'status' => $md['status']];

                        // First H1
                        $h1s = $xpath->query('//h1');
                        $firstH1 = $h1s->length ? trim($h1s->item(0)->textContent) : '';
                        $results[] = ['label' => 'First H1', 'value' => $firstH1 ?: 'Missing', 'status' => !empty($firstH1)];

                        // H1 Count
                        $results[] = ['label' => 'H1 Count', 'value' => $h1s->length, 'status' => ($h1s->length <= 1)];

                        // Images Missing Alt
                        $imgs = $xpath->query('//img[not(@alt) or @alt=""]');
                        $results[] = ['label' => 'Images Missing Alt', 'value' => $imgs->length, 'status' => ($imgs->length === 0)];

                        // Meta Robots
                        $rb = getSingle($xpath, "//meta[@name='robots']", "content");
                        $results[] = ['label' => 'Meta Robots', 'value' => $rb['value'], 'status' => $rb['status']];

                        // Canonical
                        $cn = getSingle($xpath, "//link[@rel='canonical']", "href");
                        $results[] = ['label' => 'Canonical', 'value' => $cn['value'], 'status' => $cn['status']];

                        // JSON-LD
                        $jld = $xpath->query("//script[@type='application/ld+json']")->length;
                        $results[] = ['label' => 'JSON-LD', 'value' => "{$jld} found", 'status' => ($jld > 0)];

                        // Microdata
                        $mdt = $xpath->query('//*[@itemscope and @itemtype]')->length;
                        $results[] = ['label' => 'Microdata', 'value' => "{$mdt} found", 'status' => ($mdt > 0)];

                        // OG Title
                        $og = getSingle($xpath, "//meta[@property='og:title']", "content");
                        $results[] = ['label' => 'OG Title', 'value' => $og['value'], 'status' => $og['status']];

                        // Twitter Card
                        $tw = getSingle($xpath, "//meta[@name='twitter:card']", "content");
                        $results[] = ['label' => 'Twitter Card', 'value' => $tw['value'], 'status' => $tw['status']];

                        // Viewport
                        $vw = getSingle($xpath, "//meta[@name='viewport']", "content");
                        $results[] = ['label' => 'Viewport', 'value' => $vw['value'], 'status' => $vw['status']];

                        // Favicon detection
                        $fv = getSingle($xpath,
                            "//link[contains(translate(@rel,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),'icon')]",
                            'href'
                        );
                        if (!$fv['status']) {
                            $fv = getSingle($xpath,
                                "//link[translate(@rel,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz')='shortcut icon']",
                                'href'
                            );
                        }
                        if (!$fv['status']) {
                            $fv = getSingle($xpath,
                                "//link[translate(@rel,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz')='icon']",
                                'href'
                            );
                        }
                        $results[] = ['label' => 'Favicon', 'value' => $fv['value'], 'status' => $fv['status']];

                        // HTML Lang
                        $htmlTag = $doc->getElementsByTagName('html')->item(0);
                        if ($htmlTag && $htmlTag->hasAttribute('lang')) {
                            $lang = $htmlTag->getAttribute('lang');
                            $results[] = ['label' => 'HTML Lang', 'value' => $lang, 'status' => true];
                        } else {
                            $results[] = ['label' => 'HTML Lang', 'value' => 'Missing', 'status' => false];
                        }
                    }
                    $all_results[$page_url] = $results;
                }
            }
        }
    }
}

// Compute summary stats
$pagesScanned = count($all_results);
$totalFailures = 0;
foreach ($all_results as $checks) {
    foreach ($checks as $c) {
        if (!$c['status']) {
            $totalFailures++;
        }
    }
}

// Handle CSV export if requested
if (!empty($_GET['export']) && $_GET['export'] == '1' && !empty($all_results)) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=seo_audit.csv');

    $out = fopen('php://output', 'w');
    // Header row
    $firstRow = reset($all_results);
    $headers = ['URL'];
    foreach ($firstRow as $r) {
        $headers[] = $r['label'];
    }
    fputcsv($out, $headers);

    // Data rows
    foreach ($all_results as $page => $checks) {
        $row = [$page];
        foreach ($checks as $c) {
            $row[] = ($c['status'] ? 'PASS' : 'FAIL') . ' – ' . $c['value'];
        }
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>SEO Sitemap Audit – Responsive Dashboard</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Review sitemap URLs on any device with responsive tables that highlight metadata, headings, and performance issues in the BREN7 SEO Sitemap Audit tool.">
  <meta name="keywords" content="responsive seo audit, sitemap checker, metadata compliance, mobile seo monitoring, Morweb tools">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="SEO Sitemap Audit – Responsive Dashboard">
  <meta property="og:description" content="Monitor SEO signals across sitemap URLs with a mobile-friendly dashboard from BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/sitemap-seo-audit-responsive.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="SEO Sitemap Audit – Responsive Dashboard">
  <meta name="twitter:description" content="Track SEO checks across devices with the responsive sitemap audit from BREN7.">
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
      --btn-hover: #1E4CB0;
      --fade-primary-blue: rgba(37, 99, 235, 0.04);
      --fade-light-gray: rgba(248, 250, 252, 0.5);
      --fade-error-red: rgba(239, 68, 68, 0.1);
      --fade-success-green: rgba(34, 197, 94, 0.1);
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
      max-width: 96%;
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

    /* Instructions */
    .instructions ul {
      list-style-type: disc;
      padding-left: 1.5rem;
      color: var(--neutral-gray);
    }
    .instructions li {
      margin-bottom: 0.5rem;
    }
    .instructions code {
      background: var(--light-gray);
      padding: 0.2rem 0.4rem;
      border-radius: 4px;
      font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
      color: var(--primary-blue);
      font-size: 0.9rem;
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
      display: flex;
      gap: 0.75rem;
    }
    .input-wrapper {
      position: relative;
      display: flex;
      gap: 0.75rem;
      align-items: stretch;
      width: 100%;
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
      background: var(--accent-green);
      color: var(--white);
    }
    .btn-primary:hover {
      background: var(--btn-hover);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .btn:disabled {
      background: var(--neutral-gray);
      cursor: not-allowed;
      transform: none;
    }
    .btn-export {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.875rem 1.5rem;
      border: none;
      border-radius: 8px;
      color: var(--white);
      background: var(--accent-green);
      cursor: pointer;
      margin-bottom: 1rem;
      transition: background 0.3s ease;
    }
    .btn-export:hover {
      background: var(--btn-hover);
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
      .btn, .btn-export {
        justify-content: center;
      }
      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1><i class="fas fa-sitemap"></i> SEO Sitemap Audit</h1>
      <p class="subtitle">Audit each page in your sitemap for essential SEO elements</p>
    </div>

    <div class="card instructions">
      <h2><i class="fas fa-list-ol"></i> How It Works</h2>
      <ul>
        <li>Enter your full sitemap URL (e.g., <code>https://example.com/sitemap.xml</code>)</li>
        <li>Click <strong>Run Audit</strong> to fetch and analyze each page</li>
        <li>Review detailed check results for each URL</li>
        <li>Export findings as CSV for further review</li>
      </ul>
    </div>

    <div class="card form-section">
      <h2><i class="fas fa-cog"></i> Configuration</h2>
      <form method="get" id="audit-form">
        <div class="input-wrapper">
          <input
            type="url"
            name="sitemap_url"
            placeholder="https://example.com/sitemap.xml"
            value="<?php echo htmlspecialchars($sitemap_url); ?>"
            required
          >
          <button type="submit" class="btn btn-primary" id="scan-btn">
            <i class="fas fa-play"></i>
            Run Audit
          </button>
        </div>
      </form>
    </div>

    <div id="status" class="status"></div>

    <?php if ($error): ?>
      <div id="status" class="status error" style="display: block;">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($all_results)): ?>
      <div class="results-container" id="results-container">
        <div class="card summary-section">
          <h2><i class="fas fa-chart-bar"></i> Summary</h2>
          <div class="stats-grid">
            <div class="stat-card">
              <span class="stat-number"><?php echo $pagesScanned; ?></span>
              <div class="stat-label">Pages Scanned</div>
            </div>
            <div class="stat-card">
              <span class="stat-number"><?php echo $totalFailures; ?></span>
              <div class="stat-label">Total Issues Found</div>
            </div>
          </div>
        </div>

        <div class="card">
          <h2><i class="fas fa-table"></i> Detailed Results</h2>
          <a href="?sitemap_url=<?php echo urlencode($sitemap_url); ?>&export=1" class="btn-export">
            <i class="fas fa-download"></i> Export CSV
          </a>
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>URL</th>
                  <?php foreach (reset($all_results) as $r): ?>
                    <th><?php echo htmlspecialchars($r['label']); ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($all_results as $page => $checks): ?>
                  <tr>
                    <td>
                      <a
                        href="<?php echo htmlspecialchars($page); ?>"
                        target="_blank"
                        class="page-link"
                      >
                        <i class="fas fa-external-link-alt" style="font-size: 0.8em; opacity: 0.7;"></i>
                        <?php echo htmlspecialchars($page); ?>
                      </a>
                    </td>
                    <?php foreach ($checks as $c): ?>
                      <td class="<?php echo $c['status'] ? 'ok' : 'fail'; ?>">
                        <?php echo $c['status'] ? '✅' : '❌'; ?>
                      </td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script>
    $('#audit-form').on('submit', function(e) {
      e.preventDefault();
      $('#status').removeClass('error success').addClass('loading').html(
        '<div class="spinner"></div> Scanning sitemap… this may take a while...'
      ).show();
      $('#scan-btn').prop('disabled', true).html('<div class="spinner"></div> Scanning...');
      $('#results-container').hide();
      this.submit();
    });

    $(window).on('load', function() {
      if (!$('#status').hasClass('loading')) {
        $('#status').hide();
      }
      $('#scan-btn').prop('disabled', false).html('<i class="fas fa-play"></i> Run Audit');
    });
  </script>
</body>

</html>
