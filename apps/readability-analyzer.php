<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Readability Analyzer – Text Clarity Insights</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Evaluate readability scores, sentence complexity, and content clarity with the BREN7 Readability Analyzer for writers and content teams.">
  <meta name="keywords" content="readability analyzer, text clarity tool, Flesch reading ease, content analysis, writing assistant">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Readability Analyzer – Text Clarity Insights">
  <meta property="og:description" content="Measure Flesch scores, grade levels, and keyword density to optimize your copy with the BREN7 Readability Analyzer.">
  <meta property="og:url" content="https://bren7.com/apps/readability-analyzer.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Readability Analyzer – Text Clarity Insights">
  <meta name="twitter:description" content="Analyze readability scores and simplify copy with the interactive BREN7 Readability Analyzer.">
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
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
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
    
    .input-wrapper {
      display: flex;
      gap: 0.75rem;
      width: 100%;
    }

    /* Updated Field Styles */
    select, input[type="text"], textarea {
      flex: 1;
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
      position: relative;
    }

    select::placeholder, 
    input[type="text"]::placeholder, 
    textarea::placeholder {
      color: rgba(255, 255, 255, 0.7);
      font-weight: 400;
    }

    select:focus, 
    input:focus, 
    textarea:focus {
      outline: none;
      border-color: rgba(255, 255, 255, 0.8);
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 
        0 0 0 4px rgba(255, 255, 255, 0.15),
        0 8px 25px rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }

    select:hover:not(:focus), 
    input:hover:not(:focus), 
    textarea:hover:not(:focus) {
      border-color: rgba(255, 255, 255, 0.5);
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    /* Select specific styling */
    select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='rgba(255,255,255,0.8)' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 1rem center;
      background-repeat: no-repeat;
      background-size: 1rem;
      padding-right: 3rem;
    }

    select option {
      background: var(--white);
      color: var(--dark-gray);
      padding: 0.75rem;
      font-weight: 500;
    }

    /* Textarea specific styling */
    textarea {
      resize: vertical;
      min-height: 120px;
      font-family: inherit;
      line-height: 1.6;
    }

    /* Input wrapper styling for better layout */
    .form-group > div {
      width: 100%;
    }

    .form-group > div:first-child {
      min-width: 200px;
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
    }
    .status.loading {
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
      color: var(--secondary-blue);
      border-color: rgba(59, 130, 246, 0.3);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .status.error {
      background: linear-gradient(135deg, var(--fade-error-red), rgba(239, 68, 68, 0.05));
      color: var(--error-red);
      border-color: rgba(239, 68, 68, 0.3);
    }
    .status.success {
      background: linear-gradient(135deg, var(--fade-success-green), rgba(34, 197, 94, 0.05));
      color: var(--success-green);
      border-color: rgba(34, 197, 94, 0.3);
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

    /* Table Wrapper */
    .table-wrapper {
      overflow-x: auto;
      border-radius: 8px;
      border: 1px solid var(--medium-gray);
      background: var(--white);
      margin-top: 1rem;
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
      padding: 0.75rem 1rem;
      border-bottom: 1px solid var(--medium-gray);
    }
    tbody tr:hover {
      background: var(--fade-primary-blue);
    }
    tbody tr:nth-child(even) {
      background: var(--fade-light-gray);
    }

    /* Suggestions Examples */
    .example {
      background: var(--fade-secondary-blue);
      padding: 0.75rem;
      margin: 0.75rem 0;
      border-left: 4px solid var(--primary-blue);
      border-radius: 6px;
      font-style: italic;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .input-wrapper {
        flex-direction: column;
      }
      .btn, .btn-export {
        justify-content: center;
        width: 100%;
      }
      .container {
        padding: 1rem;
      }
      .header h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1><i class="fas fa-align-left"></i> Readability Analyzer</h1>
      <p class="subtitle">Measure readability and get suggestions</p>
    </div>

    <div class="card instructions">
      <h2><i class="fas fa-list-ol"></i> How It Works</h2>
      <ul>
        <li>Select whether to analyze a <strong>URL</strong> or <strong>Raw Text</strong>.</li>
        <li>For URL: enter the full page URL and click <em>Fetch & Analyze</em>.</li>
        <li>For Raw Text: paste your text into the textarea and click <em>Analyze Text</em>.</li>
        <li>View readability scores and text statistics below.</li>
        <li><strong>Note:</strong> URL analysis extracts only visible text content, excluding code and hidden elements.</li>
      </ul>
    </div>

    <div class="card form-section">
      <h2><i class="fas fa-cog"></i> Configuration</h2>
      <div class="form-group">
        <div style="flex: 1; min-width: 180px;">
          <label for="inputType" class="form-label">Input Type</label>
          <select id="inputType" class="form-select">
            <option value="text">Raw Text</option>
            <option value="url">URL</option>
          </select>
        </div>
      </div>
      <div class="form-group" id="urlGroup" style="display: none;">
        <label for="urlInput" class="form-label">Page URL</label>
        <input type="text" id="urlInput" class="form-control" placeholder="https://example.com">
      </div>
      <div class="form-group" id="textGroup">
        <label for="textInput" class="form-label">Raw Text</label>
        <textarea id="textInput" class="form-control" rows="6" placeholder="Paste your text here..."></textarea>
      </div>
      <div class="form-group">
        <button id="analyzeBtn" class="btn btn-primary"><i class="fas fa-play"></i> Analyze Text</button>
      </div>
    </div>

    <div id="status" class="status"></div>

    <div id="results-container" style="display: none;">
      <div class="card" id="results-card">
        <h2><i class="fas fa-chart-line"></i> Results</h2>
        <div id="results"></div>
      </div>

      <div class="card" id="suggestions-card">
        <h2><i class="fas fa-lightbulb"></i> Suggestions & Recommendations</h2>
        <div id="suggestions"></div>
      </div>
    </div>
  </div>

    <script>
    $('#inputType').on('change', function() {
      if (this.value === 'url') {
        $('#textGroup').hide();
        $('#urlGroup').show();
        $('#analyzeBtn').html('<i class="fas fa-download"></i> Fetch & Analyze');
      } else {
        $('#urlGroup').hide();
        $('#textGroup').show();
        $('#analyzeBtn').html('<i class="fas fa-play"></i> Analyze Text');
      }
      $('#results-container').hide();
      $('#status').hide().removeClass('error success loading');
    });

    $('#analyzeBtn').on('click', function(e) {
      e.preventDefault();
      $('#status').removeClass('error success').addClass('loading').html('<div class="spinner"></div> Scanning text…').show();
      $('#results-container').hide();

      let promise;
      if ($('#inputType').val() === 'url') {
        const url = $('#urlInput').val().trim();
        if (!url.startsWith('http')) {
          $('#status').removeClass('loading').addClass('error').text('Enter a full URL including http/https.');
          return;
        }
        promise = $.get('https://api.allorigins.win/raw?url=' + encodeURIComponent(url));
      } else {
        const text = $('#textInput').val().trim();
        if (!text) {
          $('#status').removeClass('loading').addClass('error').text('Please paste some text.');
          return;
        }
        promise = Promise.resolve(text);
      }

      promise.then(data => {
        let text;
        if ($('#inputType').val() === 'url') {
          // Extract only visible text from HTML
          text = extractVisibleText(data);
        } else {
          text = data;
        }
        analyze(text);
      }).catch(() => {
        $('#status').removeClass('loading').addClass('error').text('Failed to retrieve content.');
      });
    });

    function extractVisibleText(html) {
      // Create a temporary DOM element to parse HTML
      const $temp = $('<div>').html(html);
      
      // Remove script, style, noscript, and other non-visible elements
      $temp.find('script, style, noscript, head, meta, link, title, nav[aria-hidden="true"], [aria-hidden="true"], .sr-only, .visually-hidden, .screen-reader-only').remove();
      
      // Remove comments
      $temp.contents().filter(function() {
        return this.nodeType === 8; // Comment node
      }).remove();
      
      // Remove elements that are typically hidden
      $temp.find('[style*="display:none"], [style*="display: none"], [style*="visibility:hidden"], [style*="visibility: hidden"]').remove();
      
      // Get text content from main content areas (prioritize these)
      let mainText = '';
      const mainSelectors = ['main', 'article', '[role="main"]', '.content', '#content', '.post-content', '.entry-content', '.article-content'];
      
      for (let selector of mainSelectors) {
        const $main = $temp.find(selector);
        if ($main.length > 0) {
          mainText = $main.text();
          break;
        }
      }
      
      // If no main content found, get all text but exclude common non-content areas
      if (!mainText.trim()) {
        $temp.find('header, footer, nav, aside, .sidebar, .navigation, .menu, .ads, .advertisement, .social, .share, .comments, .comment-form, .search-form, form, .breadcrumb, .pagination').remove();
        mainText = $temp.text();
      }
      
      // Clean up the text
      return mainText
        .replace(/\s+/g, ' ') // Replace multiple whitespace with single space
        .replace(/\n\s*\n/g, '\n') // Remove excessive line breaks
        .trim();
    }

    function analyze(text) {
      if (!text.trim()) {
        $('#status').removeClass('loading').addClass('error').text('No readable text content found on this page.');
        return;
      }

      const sentences = text.match(/[^.!?]+[.!?]+/g) || [];
      const words = text.match(/\b\w+\b/g) || [];
      const syllables = words.reduce((sum, w) => sum + countSyllables(w), 0);
      const wc = words.length;
      const sc = sentences.length || 1;
      const avgWPS = wc / sc;
      const avgSPW = syllables / wc;
      const fe = 206.835 - 1.015 * (wc / sc) - 84.6 * (syllables / wc);
      const fk = 0.39 * (wc / sc) + 11.8 * (syllables / wc) - 15.59;

      $('#results').html(`
        <h2>Results</h2>
        <table class="table table-bordered">
          <tbody>
            <tr><td class="metric">Word Count</td><td>${wc}</td></tr>
            <tr><td class="metric">Sentence Count</td><td>${sc}</td></tr>
            <tr><td class="metric">Avg Words/Sentence</td><td>${avgWPS.toFixed(2)}</td></tr>
            <tr><td class="metric">Avg Syllables/Word</td><td>${avgSPW.toFixed(2)}</td></tr>
            <tr><td class="metric">Flesch Reading Ease</td><td>${fe.toFixed(2)}</td></tr>
            <tr><td class="metric">Flesch–Kincaid Grade Level</td><td>${fk.toFixed(2)}</td></tr>
          </tbody>
        </table>
      `);

      // Build suggestions with examples
      let html = '<h2>Suggestions &amp; Recommendations</h2>';
      html += '<ul>';

      // Long sentences
      const longSents = sentences.filter(s => s.split(/\s+/).length > 20);
      if (longSents.length) {
        html += `<li>Split the following long sentence (${longSents[0].split(/\s+/).length} words):<div class="example">${longSents[0].trim()}</div></li>`;
      }

      // Complex words
      const complex = words.filter(w => countSyllables(w) > 3);
      if (complex.length) {
        const uniques = [...new Set(complex)].slice(0, 3);
        html += `<li>Consider simpler alternatives to these complex words: <strong>${uniques.join(', ')}</strong>.</li>`;
      }

      // Readability low
      if (fe < 60) {
        html += `<li>Your Reading Ease is low (${fe.toFixed(2)}). Shorten sentences like:<div class="example">${sentences[0] ? sentences[0].trim() : ''}</div></li>`;
      }

      if (html === '<h2>Suggestions &amp; Recommendations</h2><ul>') {
        html += '<li>Your text is well optimized for readability! 🎉</li>';
      }
      html += '</ul>';
      $('#suggestions').html(html);

      // Show results and update status
      $('#status').removeClass('loading').addClass('success').text('Analysis complete!');
      $('#results-container').show();
      $('#analyzeBtn').prop('disabled', false).html($('#inputType').val() === 'url'
        ? '<i class="fas fa-download"></i> Fetch & Analyze'
        : '<i class="fas fa-play"></i> Analyze Text'
      );
    }

    function countSyllables(w) {
      let word = w.toLowerCase().replace(/[^a-z]/g, '');
      if (word.length <= 3) return 1;
      word = word.replace(/(?:[^laeiouy]es|ed|[^laeiouy]e)$/, '').replace(/^y/, '');
      const m = word.match(/[aeiouy]{1,2}/g);
      return m ? m.length : 1;
    }
  </script>
</body>
</html>