<!DOCTYPE html>
<html lang="en">
<head>
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-1RGGXKCNB6"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);} 
    gtag('js', new Date());
    gtag('config', 'G-1RGGXKCNB6');
  </script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Projects – BREN7 Web Tools & Experiments</title>

<!-- SEO Meta -->
<meta name="description" content="Browse the full list of BREN7 web tools, experiments, and utilities. Filter by category or search to find the perfect project to explore.">
<meta name="keywords" content="BREN7 projects, web tools, accessibility tools, seo utilities, creative experiments">
<meta name="author" content="Brent">
<meta name="robots" content="index, follow">

<!-- Open Graph (Facebook, LinkedIn) -->
<meta property="og:title" content="BREN7 Projects">
<meta property="og:description" content="Filter and search through the complete library of BREN7 web tools and experiments.">
<meta property="og:url" content="https://bren7.com/projects.php">
<meta property="og:type" content="website">
<meta property="og:image" content="https://bren7.com/images/favicon.jpg">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="BREN7 Projects">
<meta name="twitter:description" content="Discover, search, and filter BREN7 web projects.">
<meta name="twitter:image" content="https://bren7.com/images/favicon.jpg">

<!-- Favicon -->
<link rel="icon" href="images/favicon.jpg" type="image/jpeg">

<!-- Stylesheets -->
<link rel="stylesheet" href="css/style.css?v=<?php echo rand(100, 999); ?>">

<!-- Fonts & Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="projects-page">
<div class="grid-background"></div>
<div class="main-wrapper">
  <header class="app-header projects-header">
    <div class="header-content">
      <div class="logo-wrapper">
        <div class="logo">BREN<span class="accent">7</span></div>
        <div class="logo-underline"></div>
      </div>
      <p class="tagline">Web Tools &amp; Experiments</p>
      <nav class="primary-nav" aria-label="Primary">
        <a href="/" class="nav-link"><i class="fas fa-home"></i> Home</a>
      </nav>
    </div>
  </header>

  <main class="projects-main">
    <section class="projects-intro">
      <h1>Explore the Project Library</h1>
      <p>Discover the full collection of interactive tools, accessibility helpers, SEO utilities, and creative experiments. Use the search and category filters to quickly find the right experience.</p>
    </section>

<?php
$projects = [
    [
        'title' => 'Trakster Beat Maker',
        'path' => 'Trakster/trakster-beat-maker.php',
        'description' => 'Create layered beats in your browser with an intuitive step sequencer and visual feedback.',
        'categories' => ['Experiments', 'Audio']
    ],
    [
        'title' => 'Accessibility Audit Suite',
        'path' => 'accessibility-audit-suite.php',
        'description' => 'Run a comprehensive accessibility check that highlights issues across content, structure, and media.',
        'categories' => ['Accessibility', 'Audit']
    ],
    [
        'title' => 'Accessibility Quick Scan',
        'path' => 'accessibility-quick-scan.php',
        'description' => 'Get a rapid assessment of critical accessibility concerns with prioritized guidance.',
        'categories' => ['Accessibility']
    ],
    [
        'title' => 'Accessible Form Builder',
        'path' => 'accessible-form-builder.php',
        'description' => 'Assemble compliant forms with labeled controls, helper text, and keyboard-friendly layouts.',
        'categories' => ['Accessibility', 'Productivity']
    ],
    [
        'title' => 'Advanced CSV Filter',
        'path' => 'advanced-csv-filter.php',
        'description' => 'Upload CSV files and slice data instantly with multi-condition filters and exports.',
        'categories' => ['Utilities', 'Data']
    ],
    [
        'title' => 'Advanced Image Editor',
        'path' => 'advanced-image-editor.php',
        'description' => 'Tweak images in-browser with cropping, filters, and adjustments before you download.',
        'categories' => ['Design', 'Utilities']
    ],
    [
        'title' => 'Animation Effects Gallery',
        'path' => 'animation-effects-gallery.php',
        'description' => 'Preview a library of CSS-powered animation presets that you can copy into your projects.',
        'categories' => ['Design', 'Experiments']
    ],
    [
        'title' => 'BREN7 Onboarding Experience',
        'path' => 'bren7-onboarding-experience.php',
        'description' => 'Walk through a mock onboarding flow that showcases motion, storytelling, and UI polish.',
        'categories' => ['Experiments', 'Design']
    ],
    [
        'title' => 'Card Builder Pro',
        'path' => 'card-builder-pro.php',
        'description' => 'Design shareable profile and product cards with editable layouts, imagery, and copy.',
        'categories' => ['Design', 'Productivity']
    ],
    [
        'title' => 'Color Palette Generator',
        'path' => 'color-palette-generator.php',
        'description' => 'Craft cohesive color palettes with harmonious suggestions and quick export options.',
        'categories' => ['Design', 'Utilities']
    ],
    [
        'title' => 'CSS Gradient Generator',
        'path' => 'css-gradient-generator.php',
        'description' => 'Build custom gradients, preview them live, and copy the CSS with a single click.',
        'categories' => ['Design', 'Utilities']
    ],
    [
        'title' => 'Flexbox Generator (Advanced)',
        'path' => 'flexbox-generator-advanced.php',
        'description' => 'Configure complex flexbox layouts with advanced controls and instant code snippets.',
        'categories' => ['Development', 'Utilities']
    ],
    [
        'title' => 'Flexbox Generator (Classic)',
        'path' => 'flexbox-generator-classic.php',
        'description' => 'Learn the fundamentals of flexbox by tweaking classic layout presets and reviewing code.',
        'categories' => ['Development', 'Utilities']
    ],
    [
        'title' => 'HEIC to JPG Converter',
        'path' => 'heic-to-jpg-converter.php',
        'description' => 'Convert HEIC images to universally supported JPG files directly in your browser.',
        'categories' => ['Utilities']
    ],
    [
        'title' => 'Keyword Density Analyzer',
        'path' => 'keyword-density-analyzer.php',
        'description' => 'Evaluate keyword usage in your copy and uncover optimization opportunities.',
        'categories' => ['SEO', 'Content']
    ],
    [
        'title' => 'Lorem Ipsum Scanner',
        'path' => 'lorem-ipsum-scanner.php',
        'description' => 'Find forgotten placeholder text before publishing your site or deliverable.',
        'categories' => ['Content', 'Quality Assurance']
    ],
    [
        'title' => 'Meta Tag Generator',
        'path' => 'meta-tag-generator.php',
        'description' => 'Generate essential meta tags, social previews, and structured data templates.',
        'categories' => ['SEO', 'Utilities']
    ],
    [
        'title' => 'Missing Alt Scanner',
        'path' => 'missing-alt-scanner.php',
        'description' => 'Crawl a page for images without alt text to improve accessibility compliance.',
        'categories' => ['Accessibility', 'SEO']
    ],
    [
        'title' => 'Mobile Friendliness Checker',
        'path' => 'mobile-friendliness-checker.php',
        'description' => 'Audit responsive performance and identify mobile usability issues.',
        'categories' => ['SEO', 'Accessibility']
    ],
    [
        'title' => 'Professional Email Builder',
        'path' => 'professional-email-builder.php',
        'description' => 'Compose polished email signatures and templates with live previews.',
        'categories' => ['Productivity', 'Design']
    ],
    [
        'title' => 'Readability Analyzer',
        'path' => 'readability-analyzer.php',
        'description' => 'Check text clarity with readability scores, sentence structure stats, and tips.',
        'categories' => ['Content', 'SEO']
    ],
    [
        'title' => 'Sitemap Content Exporter',
        'path' => 'sitemap-content-exporter.php',
        'description' => 'Convert sitemap URLs into exportable content lists for audits and planning.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap Exporter (Advanced)',
        'path' => 'sitemap-exporter-advanced.php',
        'description' => 'Pull and enrich sitemap data with filters, tagging, and multiple output formats.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap Exporter (Basic)',
        'path' => 'sitemap-exporter-basic.php',
        'description' => 'Quickly export URLs from any XML sitemap with a streamlined interface.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap Exporter (Enhanced)',
        'path' => 'sitemap-exporter-enhanced.php',
        'description' => 'Analyze sitemap health with duplicate detection, change frequency, and priority reports.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap Form Scanner',
        'path' => 'sitemap-form-scanner.php',
        'description' => 'Locate and review form experiences across all sitemap URLs.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap Image Scanner',
        'path' => 'sitemap-image-scanner.php',
        'description' => 'Review image coverage across your sitemap to spot gaps in visual content.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap Image Scanner (Lazyload)',
        'path' => 'sitemap-image-scanner-lazyload.php',
        'description' => 'Detect lazy-loaded images within sitemap URLs to ensure they are discoverable.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap Performance Audit',
        'path' => 'sitemap-performance-audit.php',
        'description' => 'Measure page performance metrics for URLs in a sitemap to improve site speed.',
        'categories' => ['SEO', 'Performance']
    ],
    [
        'title' => 'Sitemap Performance Audit (Extended)',
        'path' => 'sitemap-performance-audit-extended.php',
        'description' => 'Gain expanded performance insights with historical comparisons and exportable data.',
        'categories' => ['SEO', 'Performance']
    ],
    [
        'title' => 'Sitemap Security Scanner',
        'path' => 'sitemap-security-scanner.php',
        'description' => 'Check sitemap URLs for HTTPS readiness and common security headers.',
        'categories' => ['SEO', 'Security']
    ],
    [
        'title' => 'Sitemap SEO Audit',
        'path' => 'sitemap-seo-audit.php',
        'description' => 'Inspect sitemap URLs for meta, headings, and structural SEO signals.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap SEO Audit (Responsive)',
        'path' => 'sitemap-seo-audit-responsive.php',
        'description' => 'Evaluate SEO elements while monitoring responsive layout issues.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Sitemap Template Scanner',
        'path' => 'sitemap-template-scanner.php',
        'description' => 'Spot reusable templates and component patterns across large sites.',
        'categories' => ['SEO', 'Sitemaps']
    ],
    [
        'title' => 'Tint & Shade Generator',
        'path' => 'tint-shade-generator.php',
        'description' => 'Generate on-brand tints and shades to expand any base color.',
        'categories' => ['Design', 'Utilities']
    ],
    [
        'title' => 'Website Migration Planner',
        'path' => 'website-migration-planner.php',
        'description' => 'Map tasks, timelines, and launch checklists for website migrations.',
        'categories' => ['Productivity', 'SEO']
    ],
    [
        'title' => 'Zen Bubbles',
        'path' => 'zen-bubbles.php',
        'description' => 'Relax with a calming bubble popper featuring ambient visuals and sound.',
        'categories' => ['Experiments', 'Games']
    ]
];

$uniqueProjects = [];
foreach ($projects as $project) {
    $uniqueProjects[$project['title']] = $project;
}
$projects = array_values($uniqueProjects);

$categories = [];
foreach ($projects as $project) {
    foreach ($project['categories'] as $category) {
        $categories[$category] = true;
    }
}
$categories = array_keys($categories);
sort($categories);
?>

    <section class="projects-controls" aria-label="Project filters">
      <div class="control search-control">
        <label for="project-search" class="control-label">Search projects</label>
        <div class="control-field">
          <i class="fas fa-search"></i>
          <input type="search" id="project-search" placeholder="Type a project name or keyword" aria-describedby="search-help">
        </div>
        <p id="search-help" class="assistive-text">Results update automatically as you type.</p>
      </div>
      <div class="control filter-control">
        <label for="category-filter" class="control-label">Filter by category</label>
        <select id="category-filter" aria-label="Filter projects by category">
          <option value="all">All Categories</option>
<?php foreach ($categories as $category): ?>
          <option value="<?php echo strtolower($category); ?>"><?php echo htmlspecialchars($category); ?></option>
<?php endforeach; ?>
        </select>
      </div>
    </section>

    <section class="projects-grid" aria-live="polite">
<?php foreach ($projects as $project): 
    $categorySlugs = array_map(function ($category) { return strtolower($category); }, $project['categories']);
    $categoryData = implode('|', $categorySlugs);
?>
      <article class="project-card" data-title="<?php echo strtolower($project['title']); ?>" data-description="<?php echo strtolower($project['description']); ?>" data-categories="<?php echo $categoryData; ?>">
        <h2><?php echo htmlspecialchars($project['title']); ?></h2>
        <p class="project-description"><?php echo htmlspecialchars($project['description']); ?></p>
        <ul class="project-tags">
<?php foreach ($project['categories'] as $category): ?>
          <li><?php echo htmlspecialchars($category); ?></li>
<?php endforeach; ?>
        </ul>
        <a class="project-link" href="apps/<?php echo $project['path']; ?>" target="_blank" rel="noopener">Launch Project <i class="fas fa-arrow-up-right-from-square"></i></a>
      </article>
<?php endforeach; ?>
    </section>

    <p class="no-results" id="no-results" hidden>No projects match your current search. Try a different keyword or category.</p>
  </main>

  <footer>
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

<script>
  document.getElementById('current-year').textContent = new Date().getFullYear();

  const searchInput = document.getElementById('project-search');
  const categoryFilter = document.getElementById('category-filter');
  const projectCards = Array.from(document.querySelectorAll('.project-card'));
  const noResults = document.getElementById('no-results');

  function applyFilters() {
    const searchTerm = searchInput.value.trim().toLowerCase();
    const categoryTerm = categoryFilter.value;

    let visibleCount = 0;

    projectCards.forEach(card => {
      const matchesSearch = !searchTerm || card.dataset.title.includes(searchTerm) || card.dataset.description.includes(searchTerm);
      const matchesCategory = categoryTerm === 'all' || card.dataset.categories.split('|').includes(categoryTerm);

      if (matchesSearch && matchesCategory) {
        card.classList.remove('is-hidden');
        visibleCount += 1;
      } else {
        card.classList.add('is-hidden');
      }
    });

    if (visibleCount === 0) {
      noResults.hidden = false;
    } else {
      noResults.hidden = true;
    }
  }

  searchInput.addEventListener('input', applyFilters);
  categoryFilter.addEventListener('change', applyFilters);
</script>
</body>
</html>
