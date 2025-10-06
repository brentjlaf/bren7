<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <title>Meta Tag Generator – Fetch & Build SEO Tags</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Pull titles, descriptions, and social tags from any URL and generate ready-to-use HTML metadata with the BREN7 Meta Tag Generator.">
  <meta name="keywords" content="meta tag generator, seo metadata tool, fetch url metadata, open graph builder, head tag generator">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Meta Tag Generator – Fetch & Build SEO Tags">
  <meta property="og:description" content="Extract and customize SEO, Open Graph, and Twitter metadata from any webpage with this BREN7 tool.">
  <meta property="og:url" content="https://bren7.com/apps/meta-tag-generator.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Meta Tag Generator – Fetch & Build SEO Tags">
  <meta name="twitter:description" content="Generate complete SEO and social tags from any URL using BREN7's Meta Tag Generator.">
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

  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
  <style>
    body { padding: 2rem; }
    textarea { font-family: monospace; }
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


  <div class='container'>
    <h1 class='mb-4 text-center'>Meta Tag Generator from URL</h1>

    <div class='mb-4'>
      <h2>About</h2>
      <p>This tool fetches metadata from any public webpage URL and helps you generate the corresponding HTML meta tags for your site's <code>&lt;head&gt;</code> section.</p>
    </div>

    <div class='mb-4'>
      <h2>Instructions</h2>
      <ol>
        <li>Enter a valid URL in the 'Enter a Page URL' field (must start with http or https).</li>
        <li>Click <strong>Fetch and Fill Meta Info</strong> to load the page and auto-populate the form fields.</li>
        <li>Review and edit the values for title, description, keywords, author, robots, and viewport as needed.</li>
        <li>Click <strong>Generate Meta Tags</strong> to produce the HTML meta tags.</li>
        <li>Copy the generated tags from the output textarea and paste them into your site's <code>&lt;head&gt;</code> section.</li>
      </ol>
    </div>

    <div class='mb-3'>
      <label for='urlInput' class='form-label'>Enter a Page URL</label>
      <input type='text' class='form-control' id='urlInput' placeholder='https://example.com'>
      <button class='btn btn-secondary mt-2' id='fetchMeta'>Fetch and Fill Meta Info</button>
    </div>

    <div class='row g-3'>
      <div class='col-md-6'>
        <label for='title' class='form-label'>Page Title</label>
        <input type='text' class='form-control' id='title'>
      </div>
      <div class='col-md-6'>
        <label for='description' class='form-label'>Meta Description</label>
        <input type='text' class='form-control' id='description'>
      </div>
      <div class='col-md-6'>
        <label for='keywords' class='form-label'>Meta Keywords</label>
        <input type='text' class='form-control' id='keywords'>
      </div>
      <div class='col-md-6'>
        <label for='author' class='form-label'>Author</label>
        <input type='text' class='form-control' id='author'>
      </div>
      <div class='col-md-6'>
        <label for='robots' class='form-label'>Robots Directive</label>
        <input type='text' class='form-control' id='robots'>
      </div>
      <div class='col-md-6'>
        <label for='viewport' class='form-label'>Viewport</label>
        <input type='text' class='form-control' id='viewport'>
      </div>
      <div class='col-12 d-grid mt-3'>
        <button class='btn btn-primary' id='generate'>Generate Meta Tags</button>
      </div>
    </div>

    <div class='mt-4'>
      <label class='form-label'>Generated Meta Tags</label>
      <textarea class='form-control' id='output' rows='10' readonly></textarea>
    </div>
  </div>

  <script src='https://code.jquery.com/jquery-3.6.4.min.js'></script>
  <script>
    $('#fetchMeta').on('click', function () {
      const url = $('#urlInput').val().trim();
      if (!url.startsWith('http')) {
        alert('Please enter a valid URL including http or https.');
        return;
      }

      $('#fetchMeta').text('Fetching...');

      $.get('https://api.allorigins.win/raw?url=' + encodeURIComponent(url))
        .done(function (data) {
          const parser = new DOMParser();
          const doc = parser.parseFromString(data, 'text/html');

          const titleText = doc.querySelector('title')?.innerText || '';
          const desc = doc.querySelector('meta[name=description]')?.getAttribute('content') || '';
          const keywords = doc.querySelector('meta[name=keywords]')?.getAttribute('content') || '';
          const author = doc.querySelector('meta[name=author]')?.getAttribute('content') || '';
          const robots = doc.querySelector('meta[name=robots]')?.getAttribute('content') || '';
          const viewport = doc.querySelector('meta[name=viewport]')?.getAttribute('content') || '';

          $('#title').val(titleText);
          $('#description').val(desc);
          $('#keywords').val(keywords);
          $('#author').val(author);
          $('#robots').val(robots);
          $('#viewport').val(viewport);

          $('#fetchMeta').text('Fetch and Fill Meta Info');
        })
        .fail(function () {
          alert('Failed to load page. Please check the URL.');
          $('#fetchMeta').text('Fetch and Fill Meta Info');
        });
    });

    $('#generate').on('click', function () {
      const title = $('#title').val().trim();
      const desc = $('#description').val().trim();
      const keywords = $('#keywords').val().trim();
      const author = $('#author').val().trim();
      const robots = $('#robots').val().trim();
      const viewport = $('#viewport').val().trim();

      let metaTags = '';

      if (title) metaTags += `<title>${title}</title>\n`;
      if (desc) metaTags += `<meta name='description' content='${desc}'>\n`;
      if (keywords) metaTags += `<meta name='keywords' content='${keywords}'>\n`;
      if (author) metaTags += `<meta name='author' content='${author}'>\n`;
      if (robots) metaTags += `<meta name='robots' content='${robots}'>\n`;
      if (viewport) metaTags += `<meta name='viewport' content='${viewport}'>\n`;

      $('#output').val(metaTags);
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