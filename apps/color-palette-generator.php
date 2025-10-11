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
  <title>Color Palette Generator – BREN7</title>
  <meta name="description" content="Use BREN7’s Color Palette Generator to craft perfect color schemes for your projects." />
  <meta name="keywords" content="color palette, palette generator, BREN7, color tool, design utilities" />
  <meta name="author" content="Brent" />
  <meta name="robots" content="index, follow" />

  <!-- Open Graph (Facebook, LinkedIn) -->
  <meta property="og:title" content="Color Palette Generator – BREN7" />
  <meta property="og:description" content="Use BREN7’s Color Palette Generator to craft perfect color schemes for your projects." />
  <meta property="og:url" content="https://bren7.com/color-palette-generator" />
  <meta property="og:type" content="website" />
  <meta property="og:image" content="https://bren7.com/images/color-palette-generator.png" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Color Palette Generator – BREN7" />
  <meta name="twitter:description" content="Use BREN7’s Color Palette Generator to craft perfect color schemes for your projects." />
  <meta name="twitter:image" content="https://bren7.com/images/color-palette-generator.png" />

  <!-- Favicon -->
  <link rel="icon" href="https://bren7.com/images/favicon.jpg" type="image/jpeg" />

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <!-- Your Styles -->
  <style>
    /* reset & layout */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background-color: #1a1a1a;
      color: white;
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    .container { max-width: 1200px; margin: 0 auto; }

    /* header */
    .header {
      background: linear-gradient(135deg, #ff6b35, #ff3d71, #e91e63);
      padding: 20px; border-radius: 15px;
      text-align: center; margin-bottom: 30px;
    }
    .header h1 {
      font-size: 2.5rem; font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    /* input section */
    .input-section {
      background-color: #2a2a2a; padding: 30px;
      border-radius: 15px; margin-bottom: 30px;
    }
    .input-label {
      display: block; margin-bottom: 15px;
      font-size: 1.2rem; text-align: center;
    }
    .input-container {
      display: flex; gap: 20px;
      align-items: center; justify-content: center;
      margin-bottom: 20px;
    }
    .color-input {
      background-color: #404040;
      border: 2px solid #555;
      border-radius: 8px; padding: 15px;
      color: white; font-size: 1.1rem;
      width: 300px; outline: none;
    }
    .color-input:focus { border-color: #ff6b35; }
    .color-preview {
      width: 80px; height: 60px;
      border-radius: 8px; border: 3px solid #555;
      background-color: #3498db;
    }
    .generate-btn {
      background: linear-gradient(135deg, #ff6b35, #ff3d71);
      border: none; border-radius: 50px;
      padding: 15px 40px; color: white;
      font-size: 1.2rem; font-weight: bold;
      cursor: pointer; transition: transform 0.2s;
      display: block; margin: 0 auto;
    }
    .generate-btn:hover { transform: translateY(-2px); }

    /* options toggle */
    .options { text-align: center; margin-top: 20px; }
    .toggle-container {
      display: inline-flex; align-items: center; gap: 10px;
    }
    .toggle {
      position: relative; width: 50px; height: 25px;
      background-color: #555; border-radius: 25px;
      cursor: pointer; transition: background-color 0.3s;
    }
    .toggle.active { background-color: #ff6b35; }
    .toggle-slider {
      position: absolute; top: 2px; left: 2px;
      width: 21px; height: 21px; background: #e8f6ff;
      border-radius: 50%; transition: transform 0.3s;
      border: 1px solid rgba(92, 204, 244, 0.35);
    }
    .toggle.active .toggle-slider {
      transform: translateX(25px);
    }

    /* palette controls */
    .palette-controls {
      background-color: #2a2a2a; padding: 20px;
      border-radius: 15px; margin-bottom: 20px;
      text-align: center;
    }
    .palette-buttons {
      display: flex; flex-wrap: wrap;
      gap: 10px; justify-content: center;
      margin-bottom: 20px;
    }
    .palette-btn {
      background-color: #404040; border: 2px solid #555;
      border-radius: 8px; padding: 12px 16px;
      color: white; cursor: pointer;
      transition: all 0.3s; font-weight: 500;
    }
    .palette-btn:hover { background-color: #555; }
    .palette-btn.active {
      background-color: #ff6b35; border-color: #ff6b35;
    }

    /* results */
    .results { margin-top: 30px; }
    .palette-section {
      background-color: #2a2a2a; border-radius: 15px;
      padding: 25px; margin-bottom: 25px;
    }
    .palette-title {
      font-size: 1.4rem; font-weight: bold;
      margin-bottom: 15px; text-align: center;
      color: #ff6b35;
    }
    .palette-description {
      text-align: center; color: #aaa;
      margin-bottom: 20px; font-size: 0.95rem;
    }
    .color-row {
      display: flex; gap: 10px;
      border-radius: 10px; overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      margin-bottom: 15px;
    }
    .color-block {
      flex: 1; height: 100px; position: relative;
      cursor: pointer; transition: transform 0.2s;
      min-width: 60px;
    }
    .color-block:hover {
      transform: scale(1.05); z-index: 10;
    }
    .color-info {
      position: absolute; bottom: 8px;
      left: 50%; transform: translateX(-50%);
      background-color: rgba(0,0,0,0.8);
      color: white; padding: 4px 8px;
      border-radius: 4px; font-size: 0.8rem;
      font-weight: bold; white-space: nowrap;
    }
    .copy-palette-btn {
      background: linear-gradient(135deg, #34495e, #2c3e50);
      border: none; border-radius: 8px;
      padding: 10px 20px; color: white;
      font-size: 0.9rem; cursor: pointer;
      transition: all 0.3s;
      display: block; margin: 15px auto 0;
    }
    .copy-palette-btn:hover {
      background: linear-gradient(135deg, #2c3e50, #34495e);
      transform: translateY(-1px);
    }

    /* notification */
    .notification {
      position: fixed; top: 20px; right: 20px;
      background-color: #4CAF50; color: white;
      padding: 15px 20px; border-radius: 8px;
      display: none; z-index: 1000;
    }

    /* responsive tweaks */
    @media (max-width: 768px) {
      .input-container { flex-direction: column; }
      .color-input { width: 100%; }
      .palette-buttons { gap: 8px; }
      .palette-btn {
        padding: 10px 12px; font-size: 0.9rem;
      }
      .color-block { min-width: 50px; height: 80px; }
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
            <h1>Color Palette Generator</h1>
        </div>

        <div class="input-section">
            <label class="input-label">Enter a base hex color</label>
            <div class="input-container">
                <input type="text" class="color-input" id="colorInput" placeholder="3498db" value="3498db">
                <div class="color-preview" id="colorPreview"></div>
            </div>
            
            <button class="generate-btn" id="generateBtn">
                Generate Color Palettes 🎨
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

        <div class="palette-controls">
            <div class="palette-buttons">
                <div class="palette-btn active" data-type="all">All Palettes</div>
                <div class="palette-btn" data-type="complementary">Complementary</div>
                <div class="palette-btn" data-type="analogous">Analogous</div>
                <div class="palette-btn" data-type="triadic">Triadic</div>
                <div class="palette-btn" data-type="tetradic">Tetradic</div>
                <div class="palette-btn" data-type="split">Split-Complementary</div>
                <div class="palette-btn" data-type="monochromatic">Monochromatic</div>
            </div>
        </div>

        <div class="results" id="results"></div>
    </div>

    <div class="notification" id="notification">Copied to clipboard!</div>

    <script>
        $(document).ready(function() {
            let currentBaseColor = '3498db';
            let includeHashtag = false;
            let currentPaletteType = 'all';

            // Initialize with default color
            updateColorPreview();
            generatePalettes();

            // Input change handler
            $('#colorInput').on('input', function() {
                updateColorPreview();
            });

            // Generate button click
            $('#generateBtn').click(function() {
                generatePalettes();
            });

            // Toggle hashtag option
            $('#hashtagToggle').click(function() {
                $(this).toggleClass('active');
                includeHashtag = $(this).hasClass('active');
            });

            // Palette type buttons
            $('.palette-btn').click(function() {
                $('.palette-btn').removeClass('active');
                $(this).addClass('active');
                currentPaletteType = $(this).data('type');
                generatePalettes();
            });

            function updateColorPreview() {
                const input = $('#colorInput').val().trim().replace('#', '');
                if (isValidHex(input)) {
                    $('#colorPreview').css('background-color', '#' + input);
                    currentBaseColor = input;
                }
            }

            function generatePalettes() {
                const input = $('#colorInput').val().trim().replace('#', '');
                
                if (!isValidHex(input)) {
                    $('#results').html('<p style="text-align: center; color: #ff6b35;">Please enter a valid hex color</p>');
                    return;
                }

                currentBaseColor = input;
                const hsl = hexToHsl(currentBaseColor);
                
                const palettes = {
                    complementary: generateComplementary(hsl),
                    analogous: generateAnalogous(hsl),
                    triadic: generateTriadic(hsl),
                    tetradic: generateTetradic(hsl),
                    split: generateSplitComplementary(hsl),
                    monochromatic: generateMonochromatic(hsl)
                };

                renderPalettes(palettes);
            }

            function renderPalettes(palettes) {
                const resultsContainer = $('#results');
                resultsContainer.empty();

                const paletteInfo = {
                    complementary: {
                        title: 'Complementary',
                        description: 'Colors that are opposite each other on the color wheel'
                    },
                    analogous: {
                        title: 'Analogous',
                        description: 'Colors that are next to each other on the color wheel'
                    },
                    triadic: {
                        title: 'Triadic',
                        description: 'Three colors evenly spaced around the color wheel'
                    },
                    tetradic: {
                        title: 'Tetradic (Rectangle)',
                        description: 'Four colors arranged in two complementary pairs'
                    },
                    split: {
                        title: 'Split-Complementary',
                        description: 'Base color plus two colors adjacent to its complement'
                    },
                    monochromatic: {
                        title: 'Monochromatic',
                        description: 'Different shades, tints, and tones of the same color'
                    }
                };

                Object.keys(palettes).forEach(key => {
                    if (currentPaletteType !== 'all' && currentPaletteType !== key) return;

                    const section = $('<div class="palette-section"></div>');
                    const title = $('<div class="palette-title"></div>').text(paletteInfo[key].title);
                    const description = $('<div class="palette-description"></div>').text(paletteInfo[key].description);
                    const colorRow = $('<div class="color-row"></div>');

                    palettes[key].forEach(color => {
                        const colorBlock = createColorBlock(color);
                        colorRow.append(colorBlock);
                    });

                    const copyBtn = $('<button class="copy-palette-btn">Copy Palette</button>');
                    copyBtn.click(() => copyPalette(palettes[key]));

                    section.append(title, description, colorRow, copyBtn);
                    resultsContainer.append(section);
                });
            }

            function createColorBlock(hex) {
                const block = $('<div class="color-block"></div>');
                block.css('background-color', '#' + hex);
                
                const info = $('<div class="color-info"></div>');
                info.text('#' + hex.toLowerCase());
                block.append(info);

                block.click(function() {
                    const colorToCopy = includeHashtag ? '#' + hex : hex;
                    copyToClipboard(colorToCopy);
                    showNotification('Color copied!');
                });

                return block;
            }

            function generateComplementary(hsl) {
                const complement = [(hsl[0] + 180) % 360, hsl[1], hsl[2]];
                return [
                    currentBaseColor,
                    hslToHex(complement),
                    hslToHex([hsl[0], hsl[1], Math.max(20, hsl[2] - 20)]),
                    hslToHex([complement[0], complement[1], Math.max(20, complement[2] - 20)]),
                    hslToHex([hsl[0], hsl[1], Math.min(80, hsl[2] + 20)])
                ];
            }

            function generateAnalogous(hsl) {
                return [
                    hslToHex([(hsl[0] - 60 + 360) % 360, hsl[1], hsl[2]]),
                    hslToHex([(hsl[0] - 30 + 360) % 360, hsl[1], hsl[2]]),
                    currentBaseColor,
                    hslToHex([(hsl[0] + 30) % 360, hsl[1], hsl[2]]),
                    hslToHex([(hsl[0] + 60) % 360, hsl[1], hsl[2]])
                ];
            }

            function generateTriadic(hsl) {
                return [
                    currentBaseColor,
                    hslToHex([(hsl[0] + 120) % 360, hsl[1], hsl[2]]),
                    hslToHex([(hsl[0] + 240) % 360, hsl[1], hsl[2]]),
                    hslToHex([hsl[0], hsl[1], Math.max(20, hsl[2] - 15)]),
                    hslToHex([hsl[0], hsl[1], Math.min(80, hsl[2] + 15)])
                ];
            }

            function generateTetradic(hsl) {
                const complement = (hsl[0] + 180) % 360;
                return [
                    currentBaseColor,
                    hslToHex([(hsl[0] + 90) % 360, hsl[1], hsl[2]]),
                    hslToHex([complement, hsl[1], hsl[2]]),
                    hslToHex([(complement + 90) % 360, hsl[1], hsl[2]]),
                    hslToHex([hsl[0], hsl[1], Math.max(20, hsl[2] - 10)])
                ];
            }

            function generateSplitComplementary(hsl) {
                const complement = (hsl[0] + 180) % 360;
                return [
                    currentBaseColor,
                    hslToHex([(complement - 30 + 360) % 360, hsl[1], hsl[2]]),
                    hslToHex([(complement + 30) % 360, hsl[1], hsl[2]]),
                    hslToHex([hsl[0], hsl[1], Math.max(20, hsl[2] - 20)]),
                    hslToHex([hsl[0], hsl[1], Math.min(80, hsl[2] + 20)])
                ];
            }

            function generateMonochromatic(hsl) {
                return [
                    hslToHex([hsl[0], hsl[1], Math.max(10, hsl[2] - 30)]),
                    hslToHex([hsl[0], hsl[1], Math.max(20, hsl[2] - 15)]),
                    currentBaseColor,
                    hslToHex([hsl[0], hsl[1], Math.min(80, hsl[2] + 15)]),
                    hslToHex([hsl[0], hsl[1], Math.min(90, hsl[2] + 30)])
                ];
            }

            function hexToHsl(hex) {
                const r = parseInt(hex.substr(0, 2), 16) / 255;
                const g = parseInt(hex.substr(2, 2), 16) / 255;
                const b = parseInt(hex.substr(4, 2), 16) / 255;

                const max = Math.max(r, g, b);
                const min = Math.min(r, g, b);
                let h, s, l = (max + min) / 2;

                if (max === min) {
                    h = s = 0;
                } else {
                    const d = max - min;
                    s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                    switch (max) {
                        case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                        case g: h = (b - r) / d + 2; break;
                        case b: h = (r - g) / d + 4; break;
                    }
                    h /= 6;
                }

                return [Math.round(h * 360), Math.round(s * 100), Math.round(l * 100)];
            }

            function hslToHex(hsl) {
                let h = hsl[0] / 360;
                let s = hsl[1] / 100;
                let l = hsl[2] / 100;

                const hue2rgb = (p, q, t) => {
                    if (t < 0) t += 1;
                    if (t > 1) t -= 1;
                    if (t < 1/6) return p + (q - p) * 6 * t;
                    if (t < 1/2) return q;
                    if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
                    return p;
                };

                let r, g, b;

                if (s === 0) {
                    r = g = b = l;
                } else {
                    const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                    const p = 2 * l - q;
                    r = hue2rgb(p, q, h + 1/3);
                    g = hue2rgb(p, q, h);
                    b = hue2rgb(p, q, h - 1/3);
                }

                const toHex = (c) => {
                    const hex = Math.round(c * 255).toString(16);
                    return hex.length === 1 ? '0' + hex : hex;
                };

                return toHex(r) + toHex(g) + toHex(b);
            }

            function copyPalette(palette) {
                const colors = palette.map(color => includeHashtag ? '#' + color : color);
                copyToClipboard(colors.join(' '));
                showNotification('Palette copied!');
            }

            function isValidHex(hex) {
                return /^[0-9A-Fa-f]{6}$/.test(hex) || /^[0-9A-Fa-f]{3}$/.test(hex);
            }

            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).catch(function() {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                });
            }

            function showNotification(message) {
                $('#notification').text(message).fadeIn(200).delay(2000).fadeOut(200);
            }

            // Enter key support
            $('#colorInput').keypress(function(e) {
                if (e.which === 13) {
                    generatePalettes();
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