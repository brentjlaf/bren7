<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Card Builder Pro – Interactive UI Card Designer</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Design responsive content cards with live previews, layout presets, and exportable HTML using BREN7's Card Builder Pro.">
  <meta name="keywords" content="card builder, ui card designer, component generator, html card layout, design tool">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Card Builder Pro – Interactive UI Card Designer">
  <meta property="og:description" content="Customize typography, imagery, and actions to craft pixel-perfect content cards inside Card Builder Pro by BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/card-builder-pro.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Card Builder Pro – Interactive UI Card Designer">
  <meta name="twitter:description" content="Craft and export responsive cards instantly with Card Builder Pro by BREN7.">
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

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      margin: 0;
      padding: 16px;
      min-height: 100vh;
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
      background: white;
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .header {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 24px 40px;
      text-align: center;
    }

    .header h1 {
      margin: 0 0 4px 0;
      font-size: 1.8rem;
      font-weight: 900;
    }

    .header p {
      margin: 0;
      opacity: 0.9;
      font-size: 0.9rem;
    }

    .main {
      display: grid;
      grid-template-columns: 380px 1fr;
      min-height: 700px;
    }

    .controls {
      background: #f8fafc;
      padding: 32px;
      border-right: 1px solid #e2e8f0;
      overflow-y: auto;
    }

    .section {
      margin-bottom: 32px;
    }

    .section h3 {
      font-size: 1.1rem;
      margin: 0 0 16px 0;
      color: #1e293b;
      font-weight: 700;
    }

    .grid {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(2, 1fr);
    }

    .chip {
      padding: 12px 16px;
      background: white;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s ease;
      font-weight: 600;
      font-size: 0.9rem;
      text-align: center;
      color: #64748b;
    }

    .chip:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .chip.active {
      border-color: #667eea;
      background: #667eea;
      color: white;
    }

    .group {
      margin-bottom: 20px;
    }

    .group label {
      display: block;
      font-weight: 600;
      color: #475569;
      margin-bottom: 8px;
      font-size: 0.9rem;
    }

    input[type="text"], textarea {
      width: 100%;
      padding: 12px;
      background: white;
      color: #1e293b;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      font-family: inherit;
      font-size: 1rem;
    }

    input[type="text"]:focus, textarea:focus {
      border-color: #667eea;
      outline: none;
    }

    input[type="color"] {
      width: 60px;
      height: 40px;
      padding: 0;
      border-radius: 8px;
      border: 2px solid #e2e8f0;
      cursor: pointer;
    }

    input[type="range"] {
      width: 100%;
      height: 6px;
      border-radius: 3px;
      background: #e2e8f0;
      outline: none;
      -webkit-appearance: none;
    }

    input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #667eea;
      cursor: pointer;
    }

    input[type="checkbox"] {
      width: 18px;
      height: 18px;
    }

    .row {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .preview {
      padding: 32px;
      display: flex;
      flex-direction: column;
      background: white;
    }

    .preview h3 {
      margin: 0 0 24px 0;
      font-size: 1.2rem;
      font-weight: 700;
      color: #1e293b;
    }

    .preview-area {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8fafc;
      border-radius: 16px;
      min-height: 400px;
      margin-bottom: 24px;
      border: 2px dashed #cbd5e1;
      padding: 50px;
    }

    .code {
      background: #0f172a;
      border-radius: 16px;
      overflow: hidden;
    }

    .code-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      background: #1e293b;
    }

    .code-head h4 {
      margin: 0;
      color: white;
    }

    .copy-btn {
      background: #10b981;
      color: white;
      border: none;
      padding: 10px 18px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }

    .copy-btn:hover {
      background: #059669;
    }

    pre {
      margin: 0;
      padding: 24px;
      background: #0f172a;
      color: #e2e8f0;
      font-family: 'Monaco', monospace;
      font-size: 0.85rem;
      line-height: 1.6;
      overflow: auto;
      max-height: 400px;
    }

    .action-bar {
      display: flex;
      gap: 12px;
      margin-top: 24px;
    }

    .action-btn {
      padding: 12px 20px;
      border-radius: 8px;
      border: 2px solid #e2e8f0;
      background: white;
      color: #64748b;
      cursor: pointer;
      font-weight: 600;
    }

    .action-btn:hover {
      background: #f1f5f9;
      color: #475569;
    }

    /* Generated card styles will be injected here */
    .demo-card {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      width: 350px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .demo-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .demo-card__image {
      width: 100%;
      height: 200px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #667eea, #764ba2);
      position: relative;
    }

    .demo-card__image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .demo-card:hover .demo-card__image img {
      transform: scale(1.05);
    }

    .demo-card__content {
      padding: 24px;
    }

    .demo-card__title {
      margin: 0 0 12px 0;
      font-size: 1.25rem;
      font-weight: 700;
      color: #1e293b;
    }

    .demo-card__description {
      margin: 0 0 18px 0;
      color: #475569;
      line-height: 1.6;
    }

    .demo-card__button {
      background: #667eea;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      font-family: inherit;
    }

    .demo-card__button:hover {
      background: #5a67d8;
      transform: translateY(-2px);
    }

    /* Layout variations */
    .layout-vertical .demo-card__content {
      display: flex;
      flex-direction: column;
    }

    .layout-horizontal {
      display: flex;
      flex-direction: row;
      width: 500px;
    }

    .layout-horizontal .demo-card__image {
      width: 200px;
      height: auto;
      min-height: 200px;
    }

    .layout-horizontal .demo-card__content {
      flex: 1;
    }

    .layout-overlay {
      position: relative;
    }

    .layout-overlay .demo-card__content {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0,0,0,0.8));
      color: white;
    }

    .layout-overlay .demo-card__title {
      color: white;
    }

    .layout-overlay .demo-card__description {
      color: rgba(255,255,255,0.9);
    }

    .layout-minimal .demo-card__image {
      height: 120px;
    }

    .layout-minimal .demo-card__content {
      padding: 16px;
    }

    .layout-minimal .demo-card__title {
      font-size: 1.1rem;
      margin-bottom: 8px;
    }

    .layout-minimal .demo-card__description {
      font-size: 0.9rem;
      margin-bottom: 12px;
    }

    @media (max-width: 968px) {
      .main {
        grid-template-columns: 1fr;
      }
      
      .controls {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>Card Builder Pro</h1>
      <p>Create stunning card components with live preview and export</p>
    </header>

    <div class="main">
      <aside class="controls">
        <section class="section">
          <h3>Card Identity</h3>
          <div class="group">
            <label for="cardName">Card Name</label>
            <input type="text" id="cardName" value="feature-card" oninput="updateCard()" placeholder="my-awesome-card" />
          </div>
        </section>

        <section class="section">
          <h3>Layout Style</h3>
          <div class="grid">
            <button class="chip active" onclick="setLayout('vertical')">Vertical</button>
            <button class="chip" onclick="setLayout('horizontal')">Horizontal</button>
            <button class="chip" onclick="setLayout('overlay')">Overlay</button>
            <button class="chip" onclick="setLayout('minimal')">Minimal</button>
          </div>
        </section>

        <section class="section">
          <h3>Card Style</h3>
          <div class="grid">
            <button class="chip active" onclick="setVariant('default')">Default</button>
            <button class="chip" onclick="setVariant('bordered')">Bordered</button>
            <button class="chip" onclick="setVariant('elevated')">Elevated</button>
            <button class="chip" onclick="setVariant('glass')">Glass</button>
          </div>
        </section>

        <section class="section">
          <h3>Content</h3>
          <div class="group">
            <label for="cardTitle">Title</label>
            <input type="text" id="cardTitle" value="Amazing Feature" oninput="updateCard()" />
          </div>
          <div class="group">
            <label for="cardDescription">Description</label>
            <textarea id="cardDescription" rows="3" oninput="updateCard()">Transform your workflow with our cutting-edge solution designed for modern teams.</textarea>
          </div>
          <div class="group">
            <label for="cardButtonText">Button Text</label>
            <input type="text" id="cardButtonText" value="Learn More" oninput="updateCard()" />
          </div>
        </section>

        <section class="section">
          <h3>Dimensions</h3>
          <div class="group">
            <label for="cardWidth">Width (<span id="widthValue">350</span>px)</label>
            <input type="range" id="cardWidth" min="240" max="600" value="350" oninput="updateCard()" />
          </div>
          <div class="group">
            <label for="cardRadius">Border Radius (<span id="radiusValue">16</span>px)</label>
            <input type="range" id="cardRadius" min="0" max="40" value="16" oninput="updateCard()" />
          </div>
          <div class="group">
            <label for="cardPadding">Content Padding (<span id="paddingValue">24</span>px)</label>
            <input type="range" id="cardPadding" min="8" max="48" value="24" oninput="updateCard()" />
          </div>
        </section>

        <section class="section">
          <h3>Colors</h3>
          <div class="row">
            <div class="group">
              <label for="cardBgColor">Background</label>
              <input type="color" id="cardBgColor" value="#ffffff" oninput="updateCard()" />
            </div>
            <div class="group">
              <label for="cardButtonColor">Button</label>
              <input type="color" id="cardButtonColor" value="#667eea" oninput="updateCard()" />
            </div>
          </div>
        </section>

        <section class="section">
          <h3>Elements</h3>
          <div class="row group">
            <input type="checkbox" id="showImage" checked onchange="updateCard()" />
            <label for="showImage">Show image</label>
          </div>
          <div class="row group">
            <input type="checkbox" id="showButton" checked onchange="updateCard()" />
            <label for="showButton">Show button</label>
          </div>
        </section>

        <div class="action-bar">
          <button class="action-btn" onclick="resetCard()">Reset</button>
          <button class="action-btn" onclick="randomizeCard()">Randomize</button>
        </div>
      </aside>

      <section class="preview">
        <h3>Live Preview</h3>
        <div id="previewArea" class="preview-area">
          <div class="demo-card layout-vertical">
            <div class="demo-card__image">
              <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21,15 16,10 5,21"/>
              </svg>
            </div>
            <div class="demo-card__content">
              <h3 class="demo-card__title">Amazing Feature</h3>
              <p class="demo-card__description">Transform your workflow with our cutting-edge solution designed for modern teams.</p>
              <button class="demo-card__button">Learn More</button>
            </div>
          </div>
        </div>

        <div class="code">
          <div class="code-head">
            <h4>Generated Code</h4>
            <button class="copy-btn" onclick="copyCode()">Copy Code</button>
          </div>
          <pre id="codeContent"><!-- HTML -->
<div class="feature-card">
  <div class="feature-card__image">
    <img src="your-image.jpg" alt="Feature image" />
  </div>
  <div class="feature-card__content">
    <h3 class="feature-card__title">Amazing Feature</h3>
    <p class="feature-card__description">Transform your workflow with our cutting-edge solution designed for modern teams.</p>
    <button class="feature-card__button">Learn More</button>
  </div>
</div>

/* CSS */
.feature-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  width: 350px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}</pre>
        </div>
      </section>
    </div>
  </div>

  <script>
    let cardConfig = {
      name: 'feature-card',
      layout: 'vertical',
      variant: 'default',
      title: 'Amazing Feature',
      description: 'Transform your workflow with our cutting-edge solution designed for modern teams.',
      buttonText: 'Learn More',
      width: 350,
      radius: 16,
      padding: 24,
      bgColor: '#ffffff',
      buttonColor: '#667eea',
      showImage: true,
      showButton: true
    };

    function setLayout(layout) {
      cardConfig.layout = layout;
      
      // Update active state for layout chips
      document.querySelectorAll('.section:nth-child(2) .chip').forEach(chip => {
        chip.classList.remove('active');
      });
      event.target.classList.add('active');
      
      updateCard();
    }

    function setVariant(variant) {
      cardConfig.variant = variant;
      
      // Update active state for variant chips
      document.querySelectorAll('.section:nth-child(3) .chip').forEach(chip => {
        chip.classList.remove('active');
      });
      event.target.classList.add('active');
      
      updateCard();
    }

    function sanitizeClassName(name) {
      return name.toLowerCase()
        .replace(/[^a-z0-9-_]/g, '-')
        .replace(/--+/g, '-')
        .replace(/^-|-$/g, '') || 'card';
    }

    function getPreviewImage() {
      const images = [
        `<svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21,15 16,10 5,21"/>
        </svg>`,
        `<svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
          <path d="M9 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-4"/>
          <polyline points="9,11 12,8 15,11"/>
          <line x1="12" y1="2" x2="12" y2="11"/>
        </svg>`,
        `<svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
          <polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2"/>
        </svg>`,
        `<svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
          <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
        </svg>`
      ];
      
      return images[Math.floor(Math.random() * images.length)];
    }

    function updateCard() {
      // Update config from inputs
      cardConfig.name = sanitizeClassName(document.getElementById('cardName').value);
      cardConfig.title = document.getElementById('cardTitle').value;
      cardConfig.description = document.getElementById('cardDescription').value;
      cardConfig.buttonText = document.getElementById('cardButtonText').value;
      cardConfig.width = parseInt(document.getElementById('cardWidth').value);
      cardConfig.radius = parseInt(document.getElementById('cardRadius').value);
      cardConfig.padding = parseInt(document.getElementById('cardPadding').value);
      cardConfig.bgColor = document.getElementById('cardBgColor').value;
      cardConfig.buttonColor = document.getElementById('cardButtonColor').value;
      cardConfig.showImage = document.getElementById('showImage').checked;
      cardConfig.showButton = document.getElementById('showButton').checked;

      // Update indicators
      document.getElementById('widthValue').textContent = cardConfig.width;
      document.getElementById('radiusValue').textContent = cardConfig.radius;
      document.getElementById('paddingValue').textContent = cardConfig.padding;

      // Generate card HTML based on layout
      let imageHtml = '';
      if (cardConfig.showImage) {
        imageHtml = `<div class="${cardConfig.name}__image">${getPreviewImage()}</div>`;
      }
      
      const buttonHtml = cardConfig.showButton ? 
        `<button class="${cardConfig.name}__button">${cardConfig.buttonText}</button>` : '';

      const cardHtml = `<div class="${cardConfig.name} ${cardConfig.name}--${cardConfig.variant} layout-${cardConfig.layout}">
  ${imageHtml}
  <div class="${cardConfig.name}__content">
    <h3 class="${cardConfig.name}__title">${cardConfig.title}</h3>
    <p class="${cardConfig.name}__description">${cardConfig.description}</p>
    ${buttonHtml}
  </div>
</div>`;

      // Generate CSS based on layout and variant
      const layoutStyles = getLayoutStyles();
      const variantStyles = getVariantStyles();

      const buttonMargin = cardConfig.showButton ? `\n  margin: 0 0 ${Math.floor(cardConfig.padding * 0.75)}px 0;` : '\n  margin: 0;';

      const cardCSS = `/* ${cardConfig.name} Component */
.${cardConfig.name} {${variantStyles}
  border-radius: ${cardConfig.radius}px;
  width: ${cardConfig.layout === 'horizontal' ? Math.min(cardConfig.width * 1.4, 600) : cardConfig.width}px;
  overflow: hidden;
  transition: all 0.3s ease;${layoutStyles.container}
}

.${cardConfig.name}:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

${cardConfig.showImage ? `.${cardConfig.name}__image {
  width: 100%;
  height: ${cardConfig.layout === 'minimal' ? '120px' : '200px'};
  background: linear-gradient(135deg, #667eea, #764ba2);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;${layoutStyles.image}
}

` : ''}.${cardConfig.name}__content {
  padding: ${cardConfig.padding}px;${layoutStyles.content}
}

.${cardConfig.name}__title {
  margin: 0 0 ${Math.floor(cardConfig.padding * 0.5)}px 0;
  font-size: ${cardConfig.layout === 'minimal' ? '1.1rem' : '1.25rem'};
  font-weight: 700;
  color: ${cardConfig.layout === 'overlay' ? 'white' : '#1e293b'};
}

.${cardConfig.name}__description {${buttonMargin}
  color: ${cardConfig.layout === 'overlay' ? 'rgba(255,255,255,0.9)' : '#475569'};
  line-height: 1.6;
  font-size: ${cardConfig.layout === 'minimal' ? '0.9rem' : '1rem'};
}

${cardConfig.showButton ? `.${cardConfig.name}__button {
  background: ${cardConfig.buttonColor};
  color: white;
  border: none;
  padding: ${Math.floor(cardConfig.padding * 0.42)}px ${Math.floor(cardConfig.padding * 0.83)}px;
  border-radius: ${Math.max(cardConfig.radius - 8, 4)}px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s ease;
  font-family: inherit;
}

.${cardConfig.name}__button:hover {
  background: ${adjustColor(cardConfig.buttonColor, -20)};
  transform: translateY(-2px);
}` : ''}`;

      // Update preview
      document.getElementById('previewArea').innerHTML = cardHtml;
      
      // Update code display
      const htmlCode = cardHtml.replace(getPreviewImage(), '<img src="your-image.jpg" alt="Feature image" />');
      document.getElementById('codeContent').textContent = 
        `<!-- HTML -->\n${htmlCode}\n\n/* CSS */\n${cardCSS}`;

      // Apply styles to preview
      updatePreviewStyles();
    }

    function getLayoutStyles() {
      const styles = {
        container: '',
        image: '',
        content: ''
      };

      switch (cardConfig.layout) {
        case 'horizontal':
          styles.container = '\n  display: flex;\n  flex-direction: row;';
          styles.image = '\n  width: 200px;\n  height: auto;\n  min-height: 200px;';
          styles.content = '\n  flex: 1;';
          break;
        case 'overlay':
          styles.container = '\n  position: relative;';
          styles.content = '\n  position: absolute;\n  bottom: 0;\n  left: 0;\n  right: 0;\n  background: linear-gradient(transparent, rgba(0,0,0,0.8));\n  color: white;';
          break;
        case 'minimal':
          styles.content = '\n  padding: 16px;';
          break;
        case 'vertical':
        default:
          styles.content = '\n  display: flex;\n  flex-direction: column;';
          break;
      }

      return styles;
    }

    function getVariantStyles() {
      const variants = {
        default: `
  background: ${cardConfig.bgColor};
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);`,
        bordered: `
  background: ${cardConfig.bgColor};
  border: 2px solid #e2e8f0;
  box-shadow: none;`,
        elevated: `
  background: ${cardConfig.bgColor};
  border: none;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);`,
        glass: `
  background: ${cardConfig.bgColor}CC;
  border: 1px solid rgba(226, 232, 240, 0.4);
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);`
      };

      return variants[cardConfig.variant];
    }

    function updatePreviewStyles() {
      const existingStyle = document.getElementById('preview-styles');
      if (existingStyle) {
        existingStyle.remove();
      }

      const style = document.createElement('style');
      style.id = 'preview-styles';
      
      const layoutStyles = getLayoutStyles();
      const variantStyles = getVariantStyles();
      const buttonMargin = cardConfig.showButton ? `${Math.floor(cardConfig.padding * 0.75)}px` : '0';

      style.textContent = `
        .${cardConfig.name} {
          ${variantStyles}
          border-radius: ${cardConfig.radius}px !important;
          width: ${cardConfig.layout === 'horizontal' ? Math.min(cardConfig.width * 1.4, 600) : cardConfig.width}px !important;
          overflow: hidden;
          transition: all 0.3s ease;${layoutStyles.container}
        }
        .${cardConfig.name}:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }
        .${cardConfig.name}__image {
          height: ${cardConfig.layout === 'minimal' ? '120px' : '200px'} !important;${layoutStyles.image}
        }
        .${cardConfig.name}__content {
          padding: ${cardConfig.layout === 'minimal' ? '16px' : cardConfig.padding + 'px'} !important;${layoutStyles.content}
        }
        .${cardConfig.name}__title {
          margin: 0 0 ${Math.floor(cardConfig.padding * 0.5)}px 0 !important;
          font-size: ${cardConfig.layout === 'minimal' ? '1.1rem' : '1.25rem'} !important;
          color: ${cardConfig.layout === 'overlay' ? 'white' : '#1e293b'} !important;
        }
        .${cardConfig.name}__description {
          margin: 0 0 ${buttonMargin} 0 !important;
          color: ${cardConfig.layout === 'overlay' ? 'rgba(255,255,255,0.9)' : '#475569'} !important;
          font-size: ${cardConfig.layout === 'minimal' ? '0.9rem' : '1rem'} !important;
        }
        .${cardConfig.name}__button {
          background: ${cardConfig.buttonColor} !important;
          border-radius: ${Math.max(cardConfig.radius - 8, 4)}px !important;
          padding: ${Math.floor(cardConfig.padding * 0.42)}px ${Math.floor(cardConfig.padding * 0.83)}px !important;
        }
        .${cardConfig.name}__button:hover {
          background: ${adjustColor(cardConfig.buttonColor, -20)} !important;
        }
      `;
      
      document.head.appendChild(style);
    }

    function adjustColor(hex, amount) {
      const usePound = hex[0] === '#';
      const col = usePound ? hex.slice(1) : hex;
      const num = parseInt(col, 16);
      let r = (num >> 16) + amount;
      let g = ((num >> 8) & 0x00FF) + amount;
      let b = (num & 0x0000FF) + amount;
      r = Math.max(0, Math.min(255, r));
      g = Math.max(0, Math.min(255, g));
      b = Math.max(0, Math.min(255, b));
      return (usePound ? '#' : '') + ((r << 16) | (g << 8) | b).toString(16).padStart(6, '0');
    }

    function resetCard() {
      cardConfig = {
        name: 'feature-card',
        layout: 'vertical',
        variant: 'default',
        title: 'Amazing Feature',
        description: 'Transform your workflow with our cutting-edge solution designed for modern teams.',
        buttonText: 'Learn More',
        width: 350,
        radius: 16,
        padding: 24,
        bgColor: '#ffffff',
        buttonColor: '#667eea',
        showImage: true,
        showButton: true
      };

      // Reset form inputs
      document.getElementById('cardName').value = cardConfig.name;
      document.getElementById('cardTitle').value = cardConfig.title;
      document.getElementById('cardDescription').value = cardConfig.description;
      document.getElementById('cardButtonText').value = cardConfig.buttonText;
      document.getElementById('cardWidth').value = cardConfig.width;
      document.getElementById('cardRadius').value = cardConfig.radius;
      document.getElementById('cardPadding').value = cardConfig.padding;
      document.getElementById('cardBgColor').value = cardConfig.bgColor;
      document.getElementById('cardButtonColor').value = cardConfig.buttonColor;
      document.getElementById('showImage').checked = cardConfig.showImage;
      document.getElementById('showButton').checked = cardConfig.showButton;

      // Reset active layout and variant
      document.querySelectorAll('.chip').forEach(chip => chip.classList.remove('active'));
      document.querySelector('.section:nth-child(2) .chip').classList.add('active'); // First layout chip
      document.querySelector('.section:nth-child(3) .chip').classList.add('active'); // First variant chip

      updateCard();
    }

    function randomizeCard() {
      const layouts = ['vertical', 'horizontal', 'overlay', 'minimal'];
      const variants = ['default', 'bordered', 'elevated', 'glass'];
      const names = ['feature-card', 'product-card', 'info-card', 'promo-card', 'service-card'];
      const titles = ['Amazing Feature', 'Premium Service', 'New Product', 'Special Offer'];
      const descriptions = [
        'Transform your workflow with our cutting-edge solution.',
        'Experience the future of digital innovation today.',
        'Streamline your process with intelligent automation.',
        'Unlock new possibilities with advanced features.'
      ];
      const buttonTexts = ['Learn More', 'Get Started', 'Try Now', 'Sign Up'];
      const colors = ['#667eea', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6'];

      cardConfig.name = names[Math.floor(Math.random() * names.length)];
      cardConfig.layout = layouts[Math.floor(Math.random() * layouts.length)];
      cardConfig.variant = variants[Math.floor(Math.random() * variants.length)];
      cardConfig.title = titles[Math.floor(Math.random() * titles.length)];
      cardConfig.description = descriptions[Math.floor(Math.random() * descriptions.length)];
      cardConfig.buttonText = buttonTexts[Math.floor(Math.random() * buttonTexts.length)];
      cardConfig.width = 280 + Math.floor(Math.random() * 200);
      cardConfig.radius = Math.floor(Math.random() * 30);
      cardConfig.padding = 16 + Math.floor(Math.random() * 24);
      cardConfig.buttonColor = colors[Math.floor(Math.random() * colors.length)];
      cardConfig.showImage = Math.random() > 0.3;
      cardConfig.showButton = Math.random() > 0.2;

      // Update form inputs
      document.getElementById('cardName').value = cardConfig.name;
      document.getElementById('cardTitle').value = cardConfig.title;
      document.getElementById('cardDescription').value = cardConfig.description;
      document.getElementById('cardButtonText').value = cardConfig.buttonText;
      document.getElementById('cardWidth').value = cardConfig.width;
      document.getElementById('cardRadius').value = cardConfig.radius;
      document.getElementById('cardPadding').value = cardConfig.padding;
      document.getElementById('cardButtonColor').value = cardConfig.buttonColor;
      document.getElementById('showImage').checked = cardConfig.showImage;
      document.getElementById('showButton').checked = cardConfig.showButton;

      // Update active layout and variant
      document.querySelectorAll('.chip').forEach(chip => {
        chip.classList.remove('active');
        const chipText = chip.textContent.toLowerCase();
        if (chip.closest('.section:nth-child(2)') && chipText === cardConfig.layout) {
          chip.classList.add('active');
        }
        if (chip.closest('.section:nth-child(3)') && chipText === cardConfig.variant) {
          chip.classList.add('active');
        }
      });

      updateCard();
    }

    async function copyCode() {
      const code = document.getElementById('codeContent').textContent;
      try {
        await navigator.clipboard.writeText(code);
        const btn = document.querySelector('.copy-btn');
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy Code', 2000);
      } catch (err) {
        console.error('Failed to copy code:', err);
      }
    }

    // Initialize on load
    window.addEventListener('load', () => {
      updateCard();
    });
  </script>
</body>
</html>