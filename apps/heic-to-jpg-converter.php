<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEIC to JPG Converter – High Quality Image Conversion</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Convert HEIC photos to JPG or PNG instantly with adjustable quality settings using BREN7's browser-based converter.">
  <meta name="keywords" content="heic to jpg converter, image format converter, photo compression tool, heic to png, online image converter">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="HEIC to JPG Converter – High Quality Image Conversion">
  <meta property="og:description" content="Drag and drop HEIC files to generate shareable JPG or PNG images with custom quality controls.">
  <meta property="og:url" content="https://bren7.com/apps/heic-to-jpg-converter.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="HEIC to JPG Converter – High Quality Image Conversion">
  <meta name="twitter:description" content="Convert HEIC photos to web-friendly JPGs in your browser with BREN7's converter.">
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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: radial-gradient(circle at top, rgba(70, 97, 164, 0.55), rgba(8, 12, 24, 0.95));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(17, 27, 45, 0.9);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.45);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 10px;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 40px;
            font-size: 1.1rem;
        }

        .upload-area {
            border: 3px dashed rgba(92, 204, 244, 0.5);
            border-radius: 15px;
            padding: 60px 20px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: rgba(92, 204, 244, 0.08);
        }

        .upload-area:hover {
            border-color: rgba(92, 204, 244, 0.6);
            background: rgba(45, 154, 192, 0.16);
            transform: translateY(-2px);
        }

        .upload-area.dragover {
            border-color: #22c55e;
            background: rgba(34, 197, 94, 0.18);
        }

        .upload-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .upload-text {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .upload-subtext {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
        }

        #fileInput {
            display: none;
        }

        .quality-selector {
            margin-bottom: 30px;
            text-align: left;
        }

        .quality-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
        }

        .quality-options {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .quality-option {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 10px 15px;
            background: rgba(20, 32, 52, 0.85);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(92, 204, 244, 0.2);
        }

        .quality-option:hover {
            background: rgba(92, 204, 244, 0.18);
            border-color: rgba(92, 204, 244, 0.4);
        }

        .quality-option input[type="radio"]:checked + label {
            background: linear-gradient(135deg, #5cccf4, #2d9ac0);
            color: rgba(255, 255, 255, 0.92);
            border-color: rgba(92, 204, 244, 0.5);
        }

        .quality-option input[type="radio"] {
            display: none;
        }

        .convert-btn {
            background: linear-gradient(135deg, #5cccf4, #2d9ac0);
            color: rgba(255, 255, 255, 0.92);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            opacity: 0.5;
            pointer-events: none;
        }

        .convert-btn:enabled {
            opacity: 1;
            pointer-events: auto;
        }

        .convert-btn:enabled:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 40px rgba(92, 204, 244, 0.45);
        }

        .progress-container {
            display: none;
            margin-bottom: 30px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #5cccf4, #2d9ac0);
            width: 0%;
            transition: width 0.3s ease;
        }

        .preview-container {
            display: none;
            margin-top: 30px;
        }

        .image-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .image-preview {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .image-preview img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .image-label {
            padding: 10px;
            background: rgba(20, 32, 52, 0.85);
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
        }

        .download-btn {
            background: linear-gradient(135deg, #34d399, #0ea5e9);
            color: #0d1424;
            border: none;
            padding: 12px 30px;
            border-radius: 28px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 18px 32px rgba(52, 211, 153, 0.35);
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 26px 44px rgba(52, 211, 153, 0.45);
        }

        .file-info {
            background: rgba(20, 32, 52, 0.85);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
        }

        .info-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .error-message {
            color: #fca5a5;
            background: rgba(248, 113, 113, 0.25);
            border: 1px solid rgba(252, 165, 165, 0.45);
            border-radius: 16px;
            padding: 15px;
            margin-top: 20px;
            display: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 2rem;
            }

            .image-comparison {
                grid-template-columns: 1fr;
            }

            .quality-options {
                flex-direction: column;
                align-items: stretch;
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
        <h1>🖼️ HEIC to JPG</h1>
        <p class="subtitle">Convert your HEIC images to high-quality JPG format instantly</p>
        
        <div class="upload-area" id="uploadArea">
            <div class="upload-icon">📁</div>
            <div class="upload-text">Drop your HEIC files here</div>
            <div class="upload-subtext">or click to browse</div>
        </div>
        
        <input type="file" id="fileInput" accept=".heic,.HEIC" multiple>
        
        <div class="quality-selector">
            <span class="quality-label">Output Quality:</span>
            <div class="quality-options">
                <div class="quality-option">
                    <input type="radio" id="quality-high" name="quality" value="0.95" checked>
                    <label for="quality-high">High (95%)</label>
                </div>
                <div class="quality-option">
                    <input type="radio" id="quality-medium" name="quality" value="0.85">
                    <label for="quality-medium">Medium (85%)</label>
                </div>
                <div class="quality-option">
                    <input type="radio" id="quality-low" name="quality" value="0.75">
                    <label for="quality-low">Low (75%)</label>
                </div>
            </div>
        </div>
        
        <button class="convert-btn" id="convertBtn" disabled>Convert to JPG</button>
        
        <div class="progress-container" id="progressContainer">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>
        
        <div class="preview-container" id="previewContainer">
            <div class="file-info" id="fileInfo"></div>
            <div class="image-comparison" id="imageComparison"></div>
            <div id="downloadLinks"></div>
        </div>
        
        <div class="error-message" id="errorMessage"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/heic2any/0.0.4/heic2any.min.js"></script>
    <script>
        class HEICConverter {
            constructor() {
                this.selectedFiles = [];
                this.convertedImages = [];
                this.initializeElements();
                this.bindEvents();
            }

            initializeElements() {
                this.uploadArea = document.getElementById('uploadArea');
                this.fileInput = document.getElementById('fileInput');
                this.convertBtn = document.getElementById('convertBtn');
                this.progressContainer = document.getElementById('progressContainer');
                this.progressFill = document.getElementById('progressFill');
                this.previewContainer = document.getElementById('previewContainer');
                this.fileInfo = document.getElementById('fileInfo');
                this.imageComparison = document.getElementById('imageComparison');
                this.downloadLinks = document.getElementById('downloadLinks');
                this.errorMessage = document.getElementById('errorMessage');
            }

            bindEvents() {
                // Upload area events
                this.uploadArea.addEventListener('click', () => this.fileInput.click());
                this.uploadArea.addEventListener('dragover', this.handleDragOver.bind(this));
                this.uploadArea.addEventListener('dragleave', this.handleDragLeave.bind(this));
                this.uploadArea.addEventListener('drop', this.handleDrop.bind(this));

                // File input change
                this.fileInput.addEventListener('change', this.handleFileSelect.bind(this));

                // Convert button
                this.convertBtn.addEventListener('click', this.convertImages.bind(this));

                // Quality radio buttons
                document.querySelectorAll('input[name="quality"]').forEach(radio => {
                    radio.addEventListener('change', (e) => {
                        document.querySelectorAll('.quality-option').forEach(option => {
                            option.classList.remove('selected');
                        });
                        e.target.closest('.quality-option').classList.add('selected');
                    });
                });
            }

            handleDragOver(e) {
                e.preventDefault();
                this.uploadArea.classList.add('dragover');
            }

            handleDragLeave(e) {
                e.preventDefault();
                this.uploadArea.classList.remove('dragover');
            }

            handleDrop(e) {
                e.preventDefault();
                this.uploadArea.classList.remove('dragover');
                const files = Array.from(e.dataTransfer.files).filter(file => 
                    file.type === 'image/heic' || file.name.toLowerCase().endsWith('.heic')
                );
                this.processFiles(files);
            }

            handleFileSelect(e) {
                const files = Array.from(e.target.files);
                this.processFiles(files);
            }

            processFiles(files) {
                if (files.length === 0) {
                    this.showError('Please select valid HEIC files.');
                    return;
                }

                this.selectedFiles = files;
                this.updateUI();
                this.hideError();
            }

            updateUI() {
                if (this.selectedFiles.length > 0) {
                    this.convertBtn.disabled = false;
                    this.uploadArea.querySelector('.upload-text').textContent = 
                        `${this.selectedFiles.length} HEIC file${this.selectedFiles.length > 1 ? 's' : ''} selected`;
                    this.uploadArea.querySelector('.upload-subtext').textContent = 
                        'Click convert to process';
                }
            }

            async convertImages() {
                this.showProgress();
                this.convertBtn.disabled = true;
                this.convertedImages = [];

                const quality = parseFloat(document.querySelector('input[name="quality"]:checked').value);

                try {
                    for (let i = 0; i < this.selectedFiles.length; i++) {
                        const file = this.selectedFiles[i];
                        this.updateProgress((i / this.selectedFiles.length) * 50);

                        const jpegBlob = await heic2any({
                            blob: file,
                            toType: 'image/jpeg',
                            quality: quality
                        });

                        // Create canvas for final quality control
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        const img = new Image();

                        await new Promise((resolve, reject) => {
                            img.onload = () => {
                                canvas.width = img.naturalWidth;
                                canvas.height = img.naturalHeight;
                                ctx.drawImage(img, 0, 0);
                                
                                canvas.toBlob((finalBlob) => {
                                    if (finalBlob) {
                                        this.convertedImages.push({
                                            original: file,
                                            converted: finalBlob,
                                            originalUrl: URL.createObjectURL(file),
                                            convertedUrl: URL.createObjectURL(finalBlob)
                                        });
                                        resolve();
                                    } else {
                                        reject(new Error('Failed to create final image'));
                                    }
                                }, 'image/jpeg', quality);
                            };
                            img.onerror = reject;
                            img.src = URL.createObjectURL(jpegBlob);
                        });

                        this.updateProgress(50 + ((i + 1) / this.selectedFiles.length) * 50);
                    }

                    this.hideProgress();
                    this.showResults();

                } catch (error) {
                    console.error('Conversion error:', error);
                    this.hideProgress();
                    this.showError('Failed to convert images. Please ensure you selected valid HEIC files.');
                    this.convertBtn.disabled = false;
                }
            }

            showProgress() {
                this.progressContainer.style.display = 'block';
                this.updateProgress(0);
            }

            updateProgress(percentage) {
                this.progressFill.style.width = `${percentage}%`;
            }

            hideProgress() {
                this.progressContainer.style.display = 'none';
            }

            showResults() {
                this.displayFileInfo();
                this.displayImageComparison();
                this.createDownloadLinks();
                this.previewContainer.style.display = 'block';
                this.convertBtn.disabled = false;
            }

            displayFileInfo() {
                const totalOriginalSize = this.selectedFiles.reduce((sum, file) => sum + file.size, 0);
                const totalConvertedSize = this.convertedImages.reduce((sum, img) => sum + img.converted.size, 0);

                this.fileInfo.innerHTML = `
                    <div class="info-row">
                        <span><strong>Files converted:</strong></span>
                        <span>${this.convertedImages.length}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Original size:</strong></span>
                        <span>${this.formatFileSize(totalOriginalSize)}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Converted size:</strong></span>
                        <span>${this.formatFileSize(totalConvertedSize)}</span>
                    </div>
                    <div class="info-row">
                        <span><strong>Space saved:</strong></span>
                        <span>${this.formatFileSize(totalOriginalSize - totalConvertedSize)} (${Math.round((1 - totalConvertedSize / totalOriginalSize) * 100)}%)</span>
                    </div>
                `;
            }

            displayImageComparison() {
                this.imageComparison.innerHTML = '';
                
                // Show first image comparison if available
                if (this.convertedImages.length > 0) {
                    const firstImage = this.convertedImages[0];
                    
                    this.imageComparison.innerHTML = `
                        <div class="image-preview">
                            <img src="${firstImage.originalUrl}" alt="Original HEIC">
                            <div class="image-label">Original HEIC</div>
                        </div>
                        <div class="image-preview">
                            <img src="${firstImage.convertedUrl}" alt="Converted JPG">
                            <div class="image-label">Converted JPG</div>
                        </div>
                    `;
                }
            }

            createDownloadLinks() {
                this.downloadLinks.innerHTML = '';

                if (this.convertedImages.length === 1) {
                    const image = this.convertedImages[0];
                    const fileName = image.original.name.replace(/\.heic$/i, '.jpg');
                    
                    const downloadBtn = document.createElement('a');
                    downloadBtn.href = image.convertedUrl;
                    downloadBtn.download = fileName;
                    downloadBtn.className = 'download-btn';
                    downloadBtn.textContent = '⬇️ Download JPG';
                    
                    this.downloadLinks.appendChild(downloadBtn);
                } else {
                    // Multiple files - create download all as ZIP (simplified version)
                    this.convertedImages.forEach((image, index) => {
                        const fileName = image.original.name.replace(/\.heic$/i, '.jpg');
                        
                        const downloadBtn = document.createElement('a');
                        downloadBtn.href = image.convertedUrl;
                        downloadBtn.download = fileName;
                        downloadBtn.className = 'download-btn';
                        downloadBtn.style.margin = '5px';
                        downloadBtn.textContent = `⬇️ ${fileName}`;
                        
                        this.downloadLinks.appendChild(downloadBtn);
                    });
                }
            }

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            showError(message) {
                this.errorMessage.textContent = message;
                this.errorMessage.style.display = 'block';
            }

            hideError() {
                this.errorMessage.style.display = 'none';
            }
        }

        // Initialize the converter when the DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new HEICConverter();
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