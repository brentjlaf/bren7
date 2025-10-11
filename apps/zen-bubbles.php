<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Zen Bubbles – Relaxing Bubble Pop Game</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Unwind with Zen Bubbles, a calming browser game where you pop glowing bubbles beneath a starry sky.">
  <meta name="keywords" content="Zen Bubbles, relaxing browser game, bubble pop game, stress relief game, calming web game">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Zen Bubbles – Relaxing Bubble Pop Game">
  <meta property="og:description" content="Float away stress by popping luminous bubbles in this soothing browser experience from BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/zen-bubbles.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/zen-bubbles-share.png">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Zen Bubbles – Relaxing Bubble Pop Game">
  <meta name="twitter:description" content="Pop pastel bubbles and relax under the stars with Zen Bubbles by BREN7.">
  <meta name="twitter:image" content="https://bren7.com/images/zen-bubbles-share.png">

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


  <!-- Favicon (optional) -->
  <link rel="icon" href="https://bren7.com/favicon.ico" type="image/x-icon" />

  <style>
    /* Reset defaults and full-screen layout */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      width: 100%;
      height: 100%;
      overflow: hidden;
      font-family: 'Segoe UI', sans-serif;
    }
    /* Dark background for the night sky */
    body {
      background: #0a192f;
    }
    /* Container for the animated stars (behind the game area) */
    #stars {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }
    /* Styling for individual stars */
    .star {
      position: absolute;
      background: #e8f6ff;
      border-radius: 50%;
      opacity: 0.5;
      animation: twinkle linear infinite;
    }
    /* Twinkle animation for stars */
    @keyframes twinkle {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }
    /* Score display styling */
    #score {
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 24px;
      color: #cfcfcf;
      text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
      z-index: 10;
    }
    /* Game area container for bubbles */
    #gameArea {
      position: relative;
      width: 100vw;
      height: 100vh;
      z-index: 5; /* Positioned above the stars */
    }
    /* Styling for each bubble */
    .bubble {
      position: absolute;
      border-radius: 50%;
      cursor: pointer;
      transition: transform 0.6s ease, opacity 0.6s ease;
      /* Fall animation settings; duration set dynamically */
      animation-name: fall;
      animation-timing-function: linear;
      animation-fill-mode: forwards;
    }
    /* Gentle pop animation for clicked bubbles */
    .bubble.pop {
      transform: scale(1.2);
      opacity: 0;
    }
    /* Keyframes for smooth falling from top to bottom */
    @keyframes fall {
      0% { transform: translateY(0); }
      100% { transform: translateY(110vh); }
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

  <!-- Container for the twinkling stars -->
  <div id="stars"></div>
  <!-- Score display -->
  <div id="score">Score: 0</div>
  <!-- Game area for falling bubbles -->
  <div id="gameArea"></div>

  <script>
    // Create animated stars in the background
    function createStars() {
      const starsContainer = document.getElementById("stars");
      const starCount = 100; // Adjust the number of stars as desired
      for (let i = 0; i < starCount; i++) {
        const star = document.createElement("div");
        star.classList.add("star");
        // Random size between 1px and 3px
        const size = Math.random() * 2 + 1;
        star.style.width = size + "px";
        star.style.height = size + "px";
        // Randomly position the star in the viewport
        star.style.left = Math.random() * 100 + "vw";
        star.style.top = Math.random() * 100 + "vh";
        // Randomize twinkling speed
        star.style.animationDuration = (Math.random() * 5 + 3) + "s";
        star.style.animationDelay = Math.random() * 5 + "s";
        starsContainer.appendChild(star);
      }
    }
    createStars();

    // Bubble click game logic
    let score = 0;
    const scoreDisplay = document.getElementById('score');
    const gameArea = document.getElementById('gameArea');

    function createBubble() {
      const bubble = document.createElement('div');
      bubble.classList.add('bubble');

      // Random size between 30px and 80px
      const size = Math.random() * 50 + 30;
      bubble.style.width = size + 'px';
      bubble.style.height = size + 'px';

      // Random horizontal position (within viewport)
      const x = Math.random() * (window.innerWidth - size);
      bubble.style.left = x + 'px';

      // Start above the viewport
      bubble.style.top = `-${size}px`;

      // Set a random falling duration (for smooth movement) between 10 and 15 seconds
      const duration = Math.random() * 5 + 10;
      bubble.style.animationDuration = duration + 's';

      // Use soft pastel colors for a calming effect
      const hue = Math.floor(Math.random() * 360);
      const pastel = `hsla(${hue}, 40%, 80%, 0.8)`;
      bubble.style.background = `radial-gradient(circle, ${pastel} 0%, rgba(255,255,255,0.2) 70%, transparent 100%)`;
      bubble.style.boxShadow = `0 0 10px ${pastel}`;

      gameArea.appendChild(bubble);

      // Remove the bubble when its falling animation ends
      bubble.addEventListener('animationend', () => {
        bubble.remove();
      });

      // When the bubble is clicked, gently pop it and update the score
      bubble.addEventListener('click', () => {
        bubble.classList.add('pop');
        score++;
        scoreDisplay.textContent = 'Score: ' + score;
        setTimeout(() => bubble.remove(), 600);
      });
    }

    // Spawn bubbles at a gentle, relaxed pace (every 1.5 seconds)
    setInterval(createBubble, 1500);

    // Adjust game area dimensions if the window is resized
    window.addEventListener('resize', () => {
      gameArea.style.width = window.innerWidth + 'px';
      gameArea.style.height = window.innerHeight + 'px';
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
