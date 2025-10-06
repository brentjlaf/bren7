<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CSS Gradient Generator – Interactive Palette Designer</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Blend colors, customize angles, and export CSS code instantly with the BREN7 CSS Gradient Generator.">
  <meta name="keywords" content="css gradient generator, color stop editor, gradient designer, css background tool, palette creator">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="CSS Gradient Generator – Interactive Palette Designer">
  <meta property="og:description" content="Design linear and radial gradients, preview combinations, and copy CSS with the BREN7 generator.">
  <meta property="og:url" content="https://bren7.com/apps/css-gradient-generator.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="CSS Gradient Generator – Interactive Palette Designer">
  <meta name="twitter:description" content="Create beautiful gradients and copy CSS instantly with BREN7's generator.">
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  
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
      max-width: 1200px;
      margin: 0 auto;
    }

    /* header */
    .header {
      background: linear-gradient(135deg, #ff6b35, #ff3d71, #e91e63, #9c27b0);
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

    /* controls section */
    .controls-section {
      background-color: #2a2a2a;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
    }

    .gradient-type {
      text-align: center;
      margin-bottom: 30px;
    }
    .gradient-type h3 {
      margin-bottom: 15px;
      font-size: 1.3rem;
    }
    .type-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .type-btn {
      background-color: #404040;
      border: 2px solid #555;
      border-radius: 8px;
      padding: 12px 20px;
      color: white;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 1rem;
    }
    .type-btn:hover {
      background-color: #555;
    }
    .type-btn.active {
      background-color: #ff6b35;
      border-color: #ff6b35;
    }

    /* color controls */
    .color-controls {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    .color-group {
      background-color: #333;
      padding: 20px;
      border-radius: 10px;
    }
    .color-group h4 {
      margin-bottom: 15px;
      text-align: center;
      color: #ff6b35;
    }
    .color-input-container {
      display: flex;
      gap: 10px;
      align-items: center;
      margin-bottom: 15px;
    }
    .color-input {
      background-color: #404040;
      border: 2px solid #555;
      border-radius: 8px;
      padding: 10px;
      color: white;
      font-size: 1rem;
      flex: 1;
      outline: none;
    }
    .color-input:focus {
      border-color: #ff6b35;
    }
    .color-picker {
      width: 50px;
      height: 40px;
      border: 2px solid #555;
      border-radius: 8px;
      background: none;
      cursor: pointer;
    }
    .add-color-btn {
      background: linear-gradient(135deg, #ff6b35, #ff3d71);
      border: none;
      border-radius: 8px;
      padding: 10px 15px;
      color: white;
      cursor: pointer;
      font-size: 0.9rem;
      transition: transform 0.2s;
    }
    .add-color-btn:hover {
      transform: translateY(-2px);
    }

    /* direction controls */
    .direction-controls {
      text-align: center;
      margin-bottom: 30px;
    }
    .direction-controls h4 {
      margin-bottom: 15px;
      color: #ff6b35;
    }
    .direction-buttons {
      display: flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .direction-btn {
      background-color: #404040;
      border: 2px solid #555;
      border-radius: 8px;
      padding: 10px 15px;
      color: white;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 0.9rem;
    }
    .direction-btn:hover {
      background-color: #555;
    }
    .direction-btn.active {
      background-color: #ff6b35;
      border-color: #ff6b35;
    }

    /* preview section */
    .preview-section {
      background-color: #2a2a2a;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
    }
    .preview-section h3 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.3rem;
      color: #ff6b35;
    }
    .gradient-preview {
      width: 100%;
      height: 200px;
      border-radius: 15px;
      border: 3px solid #555;
      background: linear-gradient(45deg, #ff6b35, #ff3d71);
      margin-bottom: 20px;
      cursor: pointer;
      transition: transform 0.2s;
    }
    .gradient-preview:hover {
      transform: scale(1.02);
    }

    /* code section */
    .code-section {
      background-color: #2a2a2a;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
    }
    .code-section h3 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.3rem;
      color: #ff6b35;
    }
    .code-output {
      background-color: #1a1a1a;
      border: 2px solid #555;
      border-radius: 10px;
      padding: 20px;
      font-family: 'Courier New', monospace;
      font-size: 0.9rem;
      color: #fff;
      white-space: pre-wrap;
      word-break: break-all;
      cursor: pointer;
      transition: border-color 0.3s;
    }
    .code-output:hover {
      border-color: #ff6b35;
    }

    /* preset gradients */
    .presets-section {
      background-color: #2a2a2a;
      padding: 30px;
      border-radius: 15px;
    }
    .presets-section h3 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.3rem;
      color: #ff6b35;
    }
    .presets-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 15px;
    }
    .preset-item {
      height: 80px;
      border-radius: 10px;
      cursor: pointer;
      transition: transform 0.2s;
      border: 2px solid #555;
    }
    .preset-item:hover {
      transform: scale(1.05);
      border-color: #ff6b35;
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

    /* responsive */
    @media (max-width: 768px) {
      .type-buttons, .direction-buttons {
        gap: 8px;
      }
      .type-btn, .direction-btn {
        padding: 8px 12px;
        font-size: 0.8rem;
      }
      .color-controls {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>CSS Gradient Generator</h1>
    </div>

    <div class="controls-section">
      <div class="gradient-type">
        <h3>Gradient Type</h3>
        <div class="type-buttons">
          <div class="type-btn active" data-type="linear">Linear</div>
          <div class="type-btn" data-type="radial">Radial</div>
          <div class="type-btn" data-type="conic">Conic</div>
        </div>
      </div>

      <div class="color-controls">
        <div class="color-group">
          <h4>Color 1</h4>
          <div class="color-input-container">
            <input type="text" class="color-input" data-color="0" value="#ff6b35" placeholder="#ff6b35">
            <input type="color" class="color-picker" data-color="0" value="#ff6b35">
          </div>
        </div>
        <div class="color-group">
          <h4>Color 2</h4>
          <div class="color-input-container">
            <input type="text" class="color-input" data-color="1" value="#ff3d71" placeholder="#ff3d71">
            <input type="color" class="color-picker" data-color="1" value="#ff3d71">
          </div>
        </div>
      </div>

      <div class="direction-controls">
        <h4>Direction</h4>
        <div class="direction-buttons">
          <div class="direction-btn" data-direction="to right">→</div>
          <div class="direction-btn" data-direction="to left">←</div>
          <div class="direction-btn" data-direction="to bottom">↓</div>
          <div class="direction-btn" data-direction="to top">↑</div>
          <div class="direction-btn active" data-direction="45deg">↗</div>
          <div class="direction-btn" data-direction="135deg">↘</div>
          <div class="direction-btn" data-direction="225deg">↙</div>
          <div class="direction-btn" data-direction="315deg">↖</div>
        </div>
      </div>

      <button class="add-color-btn" id="addColorBtn">Add Color</button>
    </div>

    <div class="preview-section">
      <h3>Preview</h3>
      <div class="gradient-preview" id="gradientPreview"></div>
    </div>

    <div class="code-section">
      <h3>CSS Code (Click to Copy)</h3>
      <div class="code-output" id="codeOutput"></div>
    </div>

    <div class="presets-section">
      <h3>Preset Gradients</h3>
      <div class="presets-grid" id="presetsGrid"></div>
    </div>
  </div>

  <div class="notification" id="notification">CSS code copied to clipboard!</div>

  <script>
    $(document).ready(function() {
      let colors = ['#ff6b35', '#ff3d71'];
      let gradientType = 'linear';
      let direction = '45deg';

      const presets = [
        { colors: ['#ff6b35', '#ff3d71'], type: 'linear', direction: '45deg' },
        { colors: ['#667eea', '#764ba2'], type: 'linear', direction: '135deg' },
        { colors: ['#f093fb', '#f5576c'], type: 'linear', direction: '45deg' },
        { colors: ['#4facfe', '#00f2fe'], type: 'linear', direction: '90deg' },
        { colors: ['#43e97b', '#38f9d7'], type: 'linear', direction: '45deg' },
        { colors: ['#fa709a', '#fee140'], type: 'linear', direction: '135deg' },
        { colors: ['#a8edea', '#fed6e3'], type: 'linear', direction: '45deg' },
        { colors: ['#ff9a9e', '#fecfef', '#fecfef'], type: 'linear', direction: '120deg' },
        { colors: ['#667eea', '#764ba2', '#667eea'], type: 'radial', direction: 'circle' },
        { colors: ['#ff6b35', '#ff3d71', '#e91e63'], type: 'conic', direction: 'from 0deg' }
      ];

      // Initialize
      updatePreview();
      generatePresets();

      // Gradient type selection
      $('.type-btn').click(function() {
        $('.type-btn').removeClass('active');
        $(this).addClass('active');
        gradientType = $(this).data('type');
        updateDirectionButtons();
        updatePreview();
      });

      // Direction selection
      $(document).on('click', '.direction-btn', function() {
        $('.direction-btn').removeClass('active');
        $(this).addClass('active');
        direction = $(this).data('direction');
        updatePreview();
      });

      // Color input changes
      $(document).on('input', '.color-input', function() {
        const index = $(this).data('color');
        const color = $(this).val();
        colors[index] = color;
        $(`.color-picker[data-color="${index}"]`).val(color);
        updatePreview();
      });

      // Color picker changes
      $(document).on('input', '.color-picker', function() {
        const index = $(this).data('color');
        const color = $(this).val();
        colors[index] = color;
        $(`.color-input[data-color="${index}"]`).val(color);
        updatePreview();
      });

      // Add color button
      $('#addColorBtn').click(function() {
        if (colors.length < 5) {
          addColorInput();
        }
      });

      // Copy code to clipboard
      $('#codeOutput').click(function() {
        const code = $(this).text();
        copyToClipboard(code);
        showNotification();
      });

      // Preview click to copy
      $('#gradientPreview').click(function() {
        const code = $('#codeOutput').text();
        copyToClipboard(code);
        showNotification();
      });

      function updateDirectionButtons() {
        const directionContainer = $('.direction-buttons');
        directionContainer.empty();
        
        if (gradientType === 'linear') {
          const directions = [
            { dir: 'to right', symbol: '→' },
            { dir: 'to left', symbol: '←' },
            { dir: 'to bottom', symbol: '↓' },
            { dir: 'to top', symbol: '↑' },
            { dir: '45deg', symbol: '↗' },
            { dir: '135deg', symbol: '↘' },
            { dir: '225deg', symbol: '↙' },
            { dir: '315deg', symbol: '↖' }
          ];
          
          directions.forEach(d => {
            const btn = $(`<div class="direction-btn" data-direction="${d.dir}">${d.symbol}</div>`);
            if (d.dir === direction) btn.addClass('active');
            directionContainer.append(btn);
          });
        } else if (gradientType === 'radial') {
          const directions = [
            { dir: 'circle', symbol: '●' },
            { dir: 'ellipse', symbol: '⬭' },
            { dir: 'circle at center', symbol: '⊙' },
            { dir: 'circle at top', symbol: '⊚' },
            { dir: 'circle at bottom', symbol: '⊛' }
          ];
          
          directions.forEach(d => {
            const btn = $(`<div class="direction-btn" data-direction="${d.dir}">${d.symbol}</div>`);
            if (d.dir === direction) btn.addClass('active');
            directionContainer.append(btn);
          });
        } else if (gradientType === 'conic') {
          const directions = [
            { dir: 'from 0deg', symbol: '↑' },
            { dir: 'from 45deg', symbol: '↗' },
            { dir: 'from 90deg', symbol: '→' },
            { dir: 'from 135deg', symbol: '↘' },
            { dir: 'from 180deg', symbol: '↓' },
            { dir: 'from 225deg', symbol: '↙' },
            { dir: 'from 270deg', symbol: '←' },
            { dir: 'from 315deg', symbol: '↖' }
          ];
          
          directions.forEach(d => {
            const btn = $(`<div class="direction-btn" data-direction="${d.dir}">${d.symbol}</div>`);
            if (d.dir === direction) btn.addClass('active');
            directionContainer.append(btn);
          });
        }
      }

      function addColorInput() {
        const colorIndex = colors.length;
        const newColor = '#' + Math.floor(Math.random()*16777215).toString(16);
        colors.push(newColor);
        
        const colorGroup = $(`
          <div class="color-group">
            <h4>Color ${colorIndex + 1}</h4>
            <div class="color-input-container">
              <input type="text" class="color-input" data-color="${colorIndex}" value="${newColor}" placeholder="${newColor}">
              <input type="color" class="color-picker" data-color="${colorIndex}" value="${newColor}">
              <button class="remove-color-btn" data-color="${colorIndex}">×</button>
            </div>
          </div>
        `);
        
        $('.color-controls').append(colorGroup);
        updatePreview();
      }

      // Remove color functionality
      $(document).on('click', '.remove-color-btn', function() {
        const index = $(this).data('color');
        colors.splice(index, 1);
        $(this).closest('.color-group').remove();
        
        // Update indices
        $('.color-input, .color-picker, .remove-color-btn').each(function() {
          const currentIndex = $(this).data('color');
          if (currentIndex > index) {
            $(this).data('color', currentIndex - 1);
            $(this).attr('data-color', currentIndex - 1);
          }
        });
        
        // Update labels
        $('.color-group').each(function(i) {
          $(this).find('h4').text(`Color ${i + 1}`);
        });
        
        updatePreview();
      });

      function updatePreview() {
        const gradient = generateGradientCSS();
        $('#gradientPreview').css('background', gradient);
        $('#codeOutput').text(`background: ${gradient};`);
      }

      function generateGradientCSS() {
        const colorString = colors.join(', ');
        
        if (gradientType === 'linear') {
          return `linear-gradient(${direction}, ${colorString})`;
        } else if (gradientType === 'radial') {
          return `radial-gradient(${direction}, ${colorString})`;
        } else if (gradientType === 'conic') {
          return `conic-gradient(${direction}, ${colorString})`;
        }
      }

      function generatePresets() {
        const grid = $('#presetsGrid');
        presets.forEach((preset, index) => {
          const colorString = preset.colors.join(', ');
          let gradient;
          
          if (preset.type === 'linear') {
            gradient = `linear-gradient(${preset.direction}, ${colorString})`;
          } else if (preset.type === 'radial') {
            gradient = `radial-gradient(${preset.direction}, ${colorString})`;
          } else if (preset.type === 'conic') {
            gradient = `conic-gradient(${preset.direction}, ${colorString})`;
          }
          
          const presetItem = $(`<div class="preset-item" data-preset="${index}"></div>`);
          presetItem.css('background', gradient);
          presetItem.click(function() {
            loadPreset(index);
          });
          grid.append(presetItem);
        });
      }

      function loadPreset(index) {
        const preset = presets[index];
        colors = [...preset.colors];
        gradientType = preset.type;
        direction = preset.direction;
        
        // Update UI
        $('.type-btn').removeClass('active');
        $(`.type-btn[data-type="${gradientType}"]`).addClass('active');
        
        // Regenerate color inputs
        $('.color-controls').empty();
        colors.forEach((color, i) => {
          const colorGroup = $(`
            <div class="color-group">
              <h4>Color ${i + 1}</h4>
              <div class="color-input-container">
                <input type="text" class="color-input" data-color="${i}" value="${color}" placeholder="${color}">
                <input type="color" class="color-picker" data-color="${i}" value="${color}">
                ${i > 1 ? `<button class="remove-color-btn" data-color="${i}">×</button>` : ''}
              </div>
            </div>
          `);
          $('.color-controls').append(colorGroup);
        });
        
        updateDirectionButtons();
        updatePreview();
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

      function showNotification() {
        $('#notification').fadeIn(200).delay(2000).fadeOut(200);
      }

      // Initialize direction buttons
      updateDirectionButtons();
    });
  </script>
</body>
</html>