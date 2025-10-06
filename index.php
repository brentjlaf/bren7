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
<title>BREN7 – Web Projects, Tools & Experiments</title>

<!-- SEO Meta -->
<meta name="description" content="Explore interactive web tools, mini-games, and experiments from BREN7. Try out Trakster Beat Maker, Zen Bubbles, accessibility tools, and more.">
<meta name="keywords" content="BREN7, web projects, beat maker, accessibility tools, game prototypes, web development, experiments, utilities">
<meta name="author" content="Brent">
<meta name="robots" content="index, follow">

<!-- Open Graph (Facebook, LinkedIn) -->
<meta property="og:title" content="BREN7 – Web Projects, Tools & Experiments">
<meta property="og:description" content="Browse through a collection of creative web tools, games, and utilities built by BREN7.">
<meta property="og:url" content="https://bren7.com/">
<meta property="og:type" content="website">
<meta property="og:image" content="https://bren7.com/images/favicon.jpg">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="BREN7 – Web Projects, Tools & Experiments">
<meta name="twitter:description" content="Interactive tools and experiments by BREN7. Explore beat makers, checkers, and more.">
<meta name="twitter:image" content="https://bren7.com/images/favicon.jpg">

<!-- Favicon -->
<link rel="icon" href="images/favicon.jpg" type="image/jpeg">

<!-- Stylesheets -->
<link rel="stylesheet" href="css/style.css?v=<?php echo rand(100, 999); ?>">

<!-- Fonts & Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
<div class="grid-background"></div>

<div class="main-wrapper">
  <header>
    <div class="header-content">
      <div class="logo-wrapper">
        <div class="logo">BREN<span class="accent">7</span></div>
        <div class="logo-underline"></div>
      </div>
      <p class="tagline">Web Experiments & Digital Innovation</p>
    </div>
  </header>

  <main class="coming-soon">
    <div class="content-box">
      <div class="status-indicator">
        <span class="pulse-dot"></span>
        <span class="status-text">SYSTEM INITIALIZING</span>
      </div>
      
      <h1 class="main-title">COMING SOON</h1>
      
      <div class="divider">
        <span class="divider-line"></span>
        <span class="divider-dot"></span>
        <span class="divider-line"></span>
      </div>
      
      <p class="subtitle">Next-generation web tools and interactive experiences are currently under development.</p>
      
      <div class="progress-container">
        <div class="progress-bar">
          <div class="progress-fill"></div>
        </div>
        <span class="progress-text">LOADING...</span>
      </div>
    </div>
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
  
  // Animated progress bar
  const progressFill = document.querySelector('.progress-fill');
  let progress = 0;
  
  setInterval(() => {
    progress = (progress + 1) % 101;
    progressFill.style.width = progress + '%';
  }, 50);
</script>
</body>
</html>