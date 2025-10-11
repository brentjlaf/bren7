<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1RGGXKCNB6"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){ dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-1RGGXKCNB6');
  </script>

  <!-- Basic Meta -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tint & Shade Generator – BREN7</title>
  <meta name="description" content="Generate perfect tints and shades of any color with BREN7’s Tint & Shade Generator." />
  <meta name="keywords" content="tint generator, shade generator, color tool, BREN7, design utilities" />
  <meta name="author" content="Brent" />
  <meta name="robots" content="index, follow" />

  <!-- Open Graph (Facebook, LinkedIn) -->
  <meta property="og:title" content="Tint & Shade Generator – BREN7" />
  <meta property="og:description" content="Generate perfect tints and shades of any color with BREN7’s Tint & Shade Generator." />
  <meta property="og:url" content="https://bren7.com/tint-shade-generator" />
  <meta property="og:type" content="website" />
  <meta property="og:image" content="https://bren7.com/images/tint-shade-generator.png" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Tint & Shade Generator – BREN7" />
  <meta name="twitter:description" content="Generate perfect tints and shades of any color with BREN7’s Tint & Shade Generator." />
  <meta name="twitter:image" content="https://bren7.com/images/tint-shade-generator.png" />

  <!-- Favicon -->
  <link rel="icon" href="https://bren7.com/images/favicon.jpg" type="image/jpeg" />

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <!-- Styles -->
  <style>
    /* reset & layout */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background-color: #1a1a1a;
      color: white;
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    .container {
      max-width: 1000px;
      margin: 0 auto;
    }

    /* header */
    .header {
      background: linear-gradient(135deg, #ff6b35, #ff3d71, #e91e63);
      padding: 20px;
      border-radius: 15px;
      text-align: center;
      margin-bottom: 30px;
    }
    .header h1 {
      font-size: 2.5rem;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    /* input section */
    .input-section {
      background-color: #2a2a2a;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
    }
    .input-label {
      display: block;
      margin-bottom: 15px;
      font-size: 1.2rem;
      text-align: center;
    }
    .input-container {
      display: flex;
      gap: 20px;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }
    .color-input {
      background-color: #404040;
      border: 2px solid #555;
      border-radius: 8px;
      padding: 15px;
      color: white;
      font-size: 1.1rem;
      width: 300px;
      outline: none;
    }
    .color-input:focus {
      border-color: #ff6b35;
    }
    .color-preview {
      width: 80px;
      height: 60px;
      border-radius: 8px;
      border: 3px solid #555;
      background-color: #290509;
    }

    .generate-btn {
      background: linear-gradient(135deg, #ff6b35, #ff3d71);
      border: none;
      border-radius: 50px;
      padding: 15px 40px;
      color: white;
      font-size: 1.2rem;
      font-weight: bold;
      cursor: pointer;
      transition: transform 0.2s;
      display: block;
      margin: 0 auto;
    }
    .generate-btn:hover {
      transform: translateY(-2px);
    }

    /* options toggle */
    .options {
      text-align: center;
      margin-top: 20px;
    }
    .toggle-container {
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }
    .toggle {
      position: relative;
      width: 50px;
      height: 25px;
      background-color: #555;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .toggle.active {
      background-color: #ff6b35;
    }
    .toggle-slider {
      position: absolute;
      top: 2px;
      left: 2px;
      width: 21px;
      height: 21px;
      background-color: #e8f6ff;
      border-radius: 50%;
      transition: transform 0.3s;
      border: 1px solid rgba(92, 204, 244, 0.35);
    }
    .toggle.active .toggle-slider {
      transform: translateX(25px);
    }

    /* percentage controls */
    .percentage-controls {
      background-color: #2a2a2a;
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 20px;
      text-align: center;
    }
    .percentage-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: center;
      margin-bottom: 20px;
    }
    .percentage-btn {
      background-color: #404040;
      border: 2px solid #555;
      border-radius: 8px;
      padding: 10px 15px;
      color: white;
      cursor: pointer;
      transition: all 0.3s;
      min-width: 60px;
    }
    .percentage-btn:hover {
      background-color: #555;
    }
    .percentage-btn.active {
      background-color: #ff6b35;
      border-color: #ff6b35;
    }

    /* results */
    .results {
      margin-top: 30px;
    }
    .color-row {
      display: flex;
      margin-bottom: 20px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    .color-block {
      flex: 1;
      height: 80px;
      position: relative;
      cursor: pointer;
      transition: transform 0.2s;
    }
    .color-block:hover {
      transform: scale(1.05);
      z-index: 10;
    }
    .color-info {
      position: absolute;
      bottom: 5px;
      left: 50%;
      transform: translateX(-50%);
      background-color: rgba(0,0,0,0.7);
      color: white;
      padding: 2px 6px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: bold;
    }

    /* notification */
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #4CAF50;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      display: none;
      z-index: 1000;
    }

    /* responsive tweaks */
    @media (max-width: 768px) {
      .input-container {
        flex-direction: column;
      }
      .color-input {
        width: 100%;
      }
      .percentage-buttons {
        gap: 5px;
      }
      .percentage-btn {
        min-width: 50px;
        padding: 8px 12px;
      }
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

    <div class="container">
        <div class="header">
            <h1>Tint &amp; Shade Generator</h1>
        </div>

        <div class="input-section">
            <label class="input-label">Enter hex colors (separated by spaces)</label>
            <div class="input-container">
                <input type="text" class="color-input" id="colorInput" placeholder="290509" value="290509">
                <div class="color-preview" id="colorPreview"></div>
            </div>
            
            <button class="generate-btn" id="generateBtn">
                Make tints and shades 🎨
            </button>

            <div class="options">
                <div class="toggle-container">
                    <span>Include hashtag when copying</span>
                    <div class="toggle" id="hashtagToggle">
                        <div class="toggle-slider"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="percentage-controls">
            <div class="percentage-buttons">
                <div class="percentage-btn active" data-percent="0">0%</div>
                <div class="percentage-btn" data-percent="10">10%</div>
                <div class="percentage-btn" data-percent="20">20%</div>
                <div class="percentage-btn" data-percent="30">30%</div>
                <div class="percentage-btn" data-percent="40">40%</div>
                <div class="percentage-btn" data-percent="50">50%</div>
                <div class="percentage-btn" data-percent="60">60%</div>
                <div class="percentage-btn" data-percent="70">70%</div>
                <div class="percentage-btn" data-percent="80">80%</div>
                <div class="percentage-btn" data-percent="90">90%</div>
                <div class="percentage-btn" data-percent="100">100%</div>
            </div>
        </div>

        <div class="results" id="results"></div>
    </div>

    <div class="notification" id="notification">Color copied to clipboard!</div>

    <script>
        $(document).ready(function() {
            let currentColors = [];
            let includeHashtag = false;

            // Initialize with default color
            updateColorPreview();
            generateColors();

            // Input change handler
            $('#colorInput').on('input', function() {
                updateColorPreview();
            });

            // Generate button click
            $('#generateBtn').click(function() {
                generateColors();
            });

            // Toggle hashtag option
            $('#hashtagToggle').click(function() {
                $(this).toggleClass('active');
                includeHashtag = $(this).hasClass('active');
            });

            // Percentage button clicks
            $('.percentage-btn').click(function() {
                $('.percentage-btn').removeClass('active');
                $(this).addClass('active');
                
                const percent = parseInt($(this).data('percent'));
                highlightPercentage(percent);
            });

            function updateColorPreview() {
                const input = $('#colorInput').val().trim();
                const colors = input.split(/\s+/).filter(color => color.length > 0);
                
                if (colors.length > 0) {
                    const firstColor = colors[0].replace('#', '');
                    if (isValidHex(firstColor)) {
                        $('#colorPreview').css('background-color', '#' + firstColor);
                    }
                }
            }

            function generateColors() {
                const input = $('#colorInput').val().trim();
                const colors = input.split(/\s+/).filter(color => color.length > 0);
                
                currentColors = colors.map(color => color.replace('#', '')).filter(isValidHex);
                
                if (currentColors.length === 0) {
                    $('#results').html('<p style="text-align: center; color: #ff6b35;">Please enter valid hex colors</p>');
                    return;
                }

                renderColorRows();
            }

            function renderColorRows() {
                const resultsContainer = $('#results');
                resultsContainer.empty();

                currentColors.forEach(baseColor => {
                    // Create shade row (darker colors)
                    const shadeRow = $('<div class="color-row"></div>');
                    for (let i = 0; i <= 100; i += 10) {
                        const shadeColor = generateShade(baseColor, i);
                        const colorBlock = createColorBlock(shadeColor, i);
                        shadeRow.append(colorBlock);
                    }
                    resultsContainer.append(shadeRow);

                    // Create tint row (lighter colors)
                    const tintRow = $('<div class="color-row"></div>');
                    for (let i = 0; i <= 100; i += 10) {
                        const tintColor = generateTint(baseColor, i);
                        const colorBlock = createColorBlock(tintColor, i);
                        tintRow.append(colorBlock);
                    }
                    resultsContainer.append(tintRow);
                });
            }

            function createColorBlock(color, percentage) {
                const block = $('<div class="color-block"></div>');
                block.css('background-color', '#' + color);
                
                const info = $('<div class="color-info"></div>');
                info.text(color.toLowerCase());
                block.append(info);

                block.click(function() {
                    const colorToCopy = includeHashtag ? '#' + color : color;
                    copyToClipboard(colorToCopy);
                    showNotification();
                });

                return block;
            }

            function generateShade(hex, percent) {
                const num = parseInt(hex, 16);
                const amt = Math.round(2.55 * percent);
                const R = (num >> 16) - amt;
                const G = (num >> 8 & 0x00FF) - amt;
                const B = (num & 0x0000FF) - amt;
                
                return (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                    (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                    (B < 255 ? B < 1 ? 0 : B : 255))
                    .toString(16)
                    .slice(1);
            }

            function generateTint(hex, percent) {
                const num = parseInt(hex, 16);
                const amt = Math.round(2.55 * percent);
                const R = (num >> 16) + amt;
                const G = (num >> 8 & 0x00FF) + amt;
                const B = (num & 0x0000FF) + amt;
                
                return (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                    (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                    (B < 255 ? B < 1 ? 0 : B : 255))
                    .toString(16)
                    .slice(1);
            }

            function highlightPercentage(targetPercent) {
                $('.color-block').each(function(index) {
                    const rowIndex = Math.floor(index / 11);
                    const colIndex = index % 11;
                    const percent = colIndex * 10;
                    
                    if (percent === targetPercent) {
                        $(this).css('box-shadow', '0 0 20px #ff6b35');
                    } else {
                        $(this).css('box-shadow', 'none');
                    }
                });
            }

            function isValidHex(hex) {
                return /^[0-9A-Fa-f]{6}$/.test(hex) || /^[0-9A-Fa-f]{3}$/.test(hex);
            }

            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).catch(function() {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                });
            }

            function showNotification() {
                $('#notification').fadeIn(200).delay(2000).fadeOut(200);
            }

            // Enter key support
            $('#colorInput').keypress(function(e) {
                if (e.which === 13) {
                    generateColors();
                }
            });
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