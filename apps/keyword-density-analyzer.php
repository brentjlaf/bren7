<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Keyword Density Analyzer – SEO Content Insights</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Measure keyword frequency, discover top phrases, and balance on-page SEO with the BREN7 Keyword Density Analyzer.">
  <meta name="keywords" content="keyword density analyzer, SEO content tool, on-page optimization, phrase frequency, copy audit">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Keyword Density Analyzer – SEO Content Insights">
  <meta property="og:description" content="Review word counts, term frequency, and content balance using the BREN7 Keyword Density Analyzer.">
  <meta property="og:url" content="https://bren7.com/apps/keyword-density-analyzer.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Keyword Density Analyzer – SEO Content Insights">
  <meta name="twitter:description" content="Discover the top keywords in your copy with the BREN7 Keyword Density Analyzer.">
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

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
      --fade-warning: rgba(245, 158, 11, 0.1);
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

    /* Container & Header */
    .container {
      max-width: 900px;
      margin: 0 auto;
      padding: 2rem;
    }
    .header {
      text-align: center;
      margin-bottom: 2rem;
      background: var(--white);
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
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
      margin-top: 0.5rem;
    }

    /* Cards */
    .card {
      background: var(--white);
      border-radius: 12px;
      padding: 1.5rem 2rem;
      margin-bottom: 1.5rem;
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

    /* Instructions & About */
    .instructions ul, .about ul {
      list-style-type: disc;
      padding-left: 1.5rem;
      color: var(--neutral-gray);
    }
    .instructions li, .about li {
      margin-bottom: 0.5rem;
    }
    .instructions code, .about code {
      background: var(--light-gray);
      padding: 0.2rem 0.4rem;
      border-radius: 4px;
      font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
      color: var(--primary-blue);
      font-size: 0.9rem;
    }
    .about p {
      color: var(--neutral-gray);
      margin-bottom: 0.75rem;
    }
    .about a {
      color: var(--primary-blue);
      text-decoration: none;
      font-weight: 500;
    }
    .about a:hover {
      text-decoration: underline;
    }

    /* Form Section */
    .form-section {
      background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
      color: var(--white);
      border: none;
    }
    .form-section h2 {
      color: var(--white);
    }
    .form-group {
      margin-bottom: 1rem;
    }
    .form-group label {
      display: block;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.95);
      margin-bottom: 0.75rem;
      font-size: 0.95rem;
      letter-spacing: 0.025em;
    }

    /* Updated Field Styles */
    input[type="text"] {
      width: 100%;
      padding: 1rem 1.25rem;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 400;
      background: rgba(255, 255, 255, 0.15);
      color: var(--white);
      backdrop-filter: blur(20px);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 1rem;
    }

    input[type="text"]::placeholder {
      color: rgba(255, 255, 255, 0.7);
      font-weight: 400;
    }

    input[type="text"]:focus {
      outline: none;
      border-color: rgba(255, 255, 255, 0.8);
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 
        0 0 0 4px rgba(255, 255, 255, 0.15),
        0 8px 25px rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }

    input[type="text"]:hover:not(:focus) {
      border-color: rgba(255, 255, 255, 0.5);
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    /* Buttons */
    .btn {
      padding: 1rem 2rem;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      white-space: nowrap;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      width: 100%;
      justify-content: center;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--accent-green), #059669);
      color: var(--white);
      border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .btn-primary:hover:not(:disabled) {
      background: linear-gradient(135deg, #059669, #047857);
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
      border-color: rgba(255, 255, 255, 0.4);
    }
    
    .btn:disabled {
      background: var(--neutral-gray);
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
      opacity: 0.6;
    }

    /* Status Messages */
    .status {
      margin: 1rem 0;
      padding: 1rem 1.25rem;
      border-radius: 10px;
      font-weight: 500;
      display: none;
      border: 1px solid transparent;
      text-align: center;
    }
    .status.loading {
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
      color: var(--secondary-blue);
      border-color: rgba(59, 130, 246, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
    }
    .status.error {
      background: linear-gradient(135deg, var(--fade-error-red), rgba(239, 68, 68, 0.05));
      color: var(--error-red);
      border-color: rgba(239, 68, 68, 0.3);
      display: block;
    }
    .status.warning {
      background: linear-gradient(135deg, var(--fade-warning), rgba(245, 158, 11, 0.05));
      color: var(--accent-orange);
      border-color: rgba(245, 158, 11, 0.3);
      display: block;
    }

    /* Spinner */
    .spinner {
      width: 18px;
      height: 18px;
      border: 2px solid transparent;
      border-top: 2px solid currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Results Section */
    .results-header {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1rem;
      color: var(--primary-blue);
      font-size: 1.2rem;
      font-weight: 600;
    }

    .total-words {
      background: linear-gradient(135deg, var(--accent-green), #059669);
      color: var(--white);
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      text-align: center;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    /* Table Wrapper */
    .table-wrapper {
      overflow-x: auto;
      border-radius: 8px;
      border: 1px solid var(--medium-gray);
      background: var(--white);
      margin-top: 1rem;
      max-height: 500px;
      overflow-y: auto;
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
    th:first-child {
      width: 50%;
    }
    th:nth-child(2) {
      width: 25%;
      text-align: center;
    }
    th:last-child {
      width: 25%;
      text-align: center;
    }
    td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid var(--medium-gray);
    }
    td:nth-child(2), td:last-child {
      text-align: center;
      font-weight: 500;
    }
    tbody tr:hover {
      background: var(--fade-primary-blue);
    }
    tbody tr:nth-child(even) {
      background: var(--fade-light-gray);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .container {
        padding: 1rem;
      }
      .header h1 {
        font-size: 2rem;
      }
      .card {
        padding: 1rem 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1><i class="fas fa-search"></i> Keyword Density Analyzer</h1>
      <p class="subtitle">Analyze word frequencies and optimize your content</p>
    </div>

    <div class="card instructions">
      <h2><i class="fas fa-list-ol"></i> Instructions</h2>
      <ul>
        <li>Enter the full URL of the page you want to analyze (including <code>http</code> or <code>https</code>).</li>
        <li>Click the <strong>Analyze Keywords</strong> button.</li>
        <li>The tool will fetch the page content and calculate word frequencies, excluding common stop words and any scripts/styles.</li>
        <li>Review the results table to see each word's count and density percentage.</li>
      </ul>
    </div>

    <div class="card about">
      <h2><i class="fas fa-info-circle"></i> About</h2>
      <p>This Keyword Density Analyzer helps you optimize your content by highlighting the most frequently used words on any web page. It uses the <a href="https://allorigins.win/" target="_blank" rel="noopener">AllOrigins</a> proxy to retrieve pages cross-origin.</p>
      <p>Built with modern web technologies for responsive design and smooth user experience. The analyzer automatically excludes common stop words and focuses on meaningful content.</p>
    </div>

    <div class="card form-section">
      <h2><i class="fas fa-cog"></i> Analyzer Configuration</h2>
      <div class="form-group">
        <label for="urlInput">Enter a Page URL</label>
        <input type="text" id="urlInput" placeholder="https://example.com">
      </div>
      <div class="form-group">
        <button class="btn btn-primary" id="analyze">
          <i class="fas fa-search"></i> Analyze Keywords
        </button>
      </div>
    </div>

    <div id="status" class="status"></div>

    <div id="results-container" style="display: none;">
      <div class="card">
        <div class="results-header">
          <i class="fas fa-chart-bar"></i>
          Keyword Analysis Results
        </div>
        <div id="results"></div>
      </div>
    </div>
  </div>

  <script>
    const stopWords = new Set([
      "the","and","is","in","it","you","of","for","on","to","a","an","this","that",
      "with","as","are","be","was","by","at","from","or","we","can","if","has","have",
      "had","they","he","she","but","not","your","our","will","would","could","should",
      "may","might","must","can't","won't","don't","didn't","doesn't","haven't","hasn't",
      "hadn't","wouldn't","couldn't","shouldn't","isn't","aren't","wasn't","weren't"
    ]);

    // Strip out all HTML tags, but first remove any <script> and <style> blocks
    function stripHTML(html) {
      const $container = $('<div>').html(html);
      $container.find('script, style, nav, header, footer').remove();
      return $container.text();
    }

    function analyzeText(text) {
      text = text
        .toLowerCase()
        .replace(/[^a-z0-9\s]/g, '')
        .trim();
      const words = text.split(/\s+/).filter(w => w && w.length > 2 && !stopWords.has(w));
      const wordCount = {};
      let totalWords = 0;

      words.forEach(word => {
        totalWords++;
        wordCount[word] = (wordCount[word] || 0) + 1;
      });

      const sorted = Object.entries(wordCount)
        .sort((a, b) => b[1] - a[1])
        .map(([word, count]) => ({
          word,
          count,
          percent: ((count / totalWords) * 100).toFixed(2) + '%'
        }));

      displayResults(sorted, totalWords);
    }

    function displayResults(data, total) {
      if (!data.length) {
        $('#status').removeClass('loading').addClass('warning').text('No keywords found in the content.');
        $('#results-container').hide();
        return;
      }

      let html = `<div class="total-words">Total Analyzed Words: ${total}</div>
                  <div class="table-wrapper">
                    <table>
                      <thead>
                        <tr>
                          <th><i class="fas fa-font"></i> Word</th>
                          <th><i class="fas fa-hashtag"></i> Count</th>
                          <th><i class="fas fa-percentage"></i> Density</th>
                        </tr>
                      </thead>
                      <tbody>`;
      
      data.slice(0, 50).forEach(({word, count, percent}) => {
        html += `<tr><td>${word}</td><td>${count}</td><td>${percent}</td></tr>`;
      });
      
      html += '</tbody></table></div>';
      
      if (data.length > 50) {
        html += `<p style="text-align: center; margin-top: 1rem; color: var(--neutral-gray); font-style: italic;">Showing top 50 keywords out of ${data.length} total unique words</p>`;
      }
      
      $('#results').html(html);
      $('#status').removeClass('loading').hide();
      $('#results-container').show();
    }

    $('#analyze').on('click', () => {
      const url = $('#urlInput').val().trim();
      if (!/^https?:\/\//i.test(url)) {
        $('#status').removeClass('loading').addClass('error').text('Please enter a full URL with http or https.').show();
        return;
      }
      
      $('#status').removeClass('error warning').addClass('loading')
        .html('<div class="spinner"></div> Fetching and analyzing page content...').show();
      $('#results-container').hide();
      $('#analyze').prop('disabled', true);

      $.get(`https://api.allorigins.win/raw?url=${encodeURIComponent(url)}`)
        .done(data => {
          const text = stripHTML(data);
          if (!text.trim()) {
            $('#status').removeClass('loading').addClass('warning').text('No readable content found on this page.');
            $('#analyze').prop('disabled', false);
            return;
          }
          analyzeText(text);
          $('#analyze').prop('disabled', false);
        })
        .fail((xhr) => {
          let errorMsg = 'Failed to fetch the page. ';
          if (xhr.status === 0) {
            errorMsg += 'Check your internet connection.';
          } else if (xhr.status === 404) {
            errorMsg += 'Page not found.';
          } else {
            errorMsg += 'Please verify the URL and try again.';
          }
          $('#status').removeClass('loading').addClass('error').text(errorMsg);
          $('#analyze').prop('disabled', false);
        });
    });

    // Enable Enter key for form submission
    $('#urlInput').on('keypress', function(e) {
      if (e.which === 13) {
        $('#analyze').click();
      }
    });
  </script>
</body>
</html>