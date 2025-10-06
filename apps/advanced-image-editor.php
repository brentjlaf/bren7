<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1RGGXKCNB6"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-1RGGXKCNB6');
  </script>

  <meta charset="UTF-8">
  <title>Advanced Image Editor</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Explore web tools, generators, and utilities from BREN7 to enhance your digital projects.">
  <meta name="keywords" content="BREN7, web tools, generators, accessibility, SEO, performance, utilities">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="BREN7 – Web Projects, Tools & Experiments">
  <meta property="og:description" content="Browse a collection of creative web tools, games, and utilities built by BREN7.">
  <meta property="og:url" content="https://bren7.com/">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="BREN7 – Web Projects, Tools & Experiments">
  <meta name="twitter:description" content="Interactive tools and experiments by BREN7. Explore beat makers, checkers, and more.">
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

  <!-- Include Cropper.js CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
  <style>
    /* Global Styles */
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    #widget-container {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 1500px;
      display: flex;
      height: 85vh;
    }
    /* Sidebar Styles */
    #sidebar {
      min-width: 300px;
      max-width: 300px;
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      margin-right: 20px;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      max-height: 100%;
    }
    /* Hide scrollbars in the settings area while still allowing scrolling */
    #settings {
      flex-grow: 1;
      overflow-y: auto;
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    #settings::-webkit-scrollbar {
      display: none;
    }
    h2 {
      font-size: 1.6rem;
      color: #333;
      margin-bottom: 20px;
    }
    .control-group {
      margin-bottom: 15px;
    }
    select, button, input[type="range"], input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #fff;
      font-size: 1rem;
      transition: all 0.2s ease-in-out;
      box-sizing: border-box;
    }
    button {
      background-color: #00a99d;
      color: white;
      border: none;
      cursor: pointer;
      transition: all 0.2s;
    }
    button:hover {
      background-color: #45a049;
    }
    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }
    #output img {
      margin-top: 20px;
      max-width: 100%;
      border-radius: 8px;
    }
    #file-size, #download-dimensions {
      font-weight: bold;
      color: #333;
    }
    #dimensions {
      margin: 10px 0;
      color: #333;
      font-weight: bold;
    }
    #warning {
      color: red;
      font-weight: bold;
      display: none;
    }
    label {
      font-weight: bold;
      color: #555;
    }
    /* Main Content Area */
    #main-content {
      flex-grow: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }
    #image-container {
      display: none;
      margin-bottom: 20px;
      width: 100%;
      height: 100%;
      position: relative;
    }
    #drop-area {
      width: 100%;
      height: 300px;
      border: 2px dashed #ccc;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #666;
      margin-bottom: 20px;
      transition: all 0.3s;
      cursor: pointer;
    }
    #drop-area.dragover {
      border-color: #00a99d;
      color: #00a99d;
    }
    /* Ensure the image container uses a canvas */
    #image-container {
      position: relative;
      width: 100%;
      height: 100%;
    }
    #image-canvas {
      max-width: 100%;
      max-height: 100%;
      border-radius: 8px;
    }
    /* Crop box dimension overlay */
    .cropper-dimensions {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: rgba(0, 0, 0, 0.5);
      color: #fff;
      padding: 5px 8px;
      border-radius: 4px;
      font-size: 14px;
      z-index: 10;
      pointer-events: none;
    }
    /* Modal Styling */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 500px;
      text-align: center;
    }
    .modal-content img {
      max-width: 100%;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
    }
    .download-button {
      padding: 10px 20px;
      background-color: #00a99d;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      margin-top: 10px;
      border-radius: 5px;
    }
    .download-button:hover {
      background-color: #45a049;
    }
    /* New styles for additional controls */
    .slider-group {
      margin-bottom: 15px;
    }
    .slider-group label {
      display: block;
      margin-bottom: 5px;
    }
    .slider-group input[type="range"] {
      width: 100%;
    }
    .filter-group {
      margin-bottom: 15px;
    }
    .filter-group label {
      display: block;
      margin-bottom: 5px;
    }
    .filter-group select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #fff;
      font-size: 1rem;
    }
    .control-buttons {
      display: flex;
      justify-content: space-between;
    }
    .control-buttons button {
      width: 48%;
    }
    /* Advanced Settings Styling */
    .advanced-settings {
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 15px;
    }
    .advanced-settings summary {
      font-size: 1.2rem;
      font-weight: bold;
      cursor: pointer;
      outline: none;
      list-style: none;
      margin-bottom: 10px;
    }
    .advanced-settings[open] {
      padding-bottom: 10px;
    }
  </style>
</head>
<body>

  <div id="widget-container">
    <!-- Sidebar for Settings -->
    <div id="sidebar">
      <h2>Image Settings</h2>
      <div id="settings">
        <!-- Crop Presets -->
        <div class="control-group">
          <label for="crop-preset">Crop Presets:</label>
          <select id="crop-preset">
            <option value="NaN">Freeform</option>
            <option value="1">1:1 (Square)</option>
            <option value="1.7777">16:9 (Wide)</option>
            <option value="1.3333">4:3 (Standard)</option>
            <option value="0.6667">2:3 (3x5)</option>
            <option value="0.75">3:4 (4x6)</option>
            <option value="0.7143">5:7</option>
          </select>
        </div>

        <!-- Rotation Controls -->
        <div class="control-group control-buttons">
          <button id="rotate-left">Rotate Left</button>
          <button id="rotate-right">Rotate Right</button>
        </div>
        <div class="slider-group">
          <label for="rotate-range">Rotate (°):</label>
          <input type="range" id="rotate-range" min="-180" max="180" step="1" value="0">
        </div>

        <!-- Flip Controls -->
        <div class="control-group control-buttons">
          <button id="flip-horizontal">Flip Horizontal</button>
          <button id="flip-vertical">Flip Vertical</button>
        </div>

        <!-- Width Scale Slider -->
        <div class="slider-group">
          <label for="wscale-range">Width Scale (0-100%):</label>
          <input type="range" id="wscale-range" min="0" max="100" step="1" value="100">
        </div>

        <!-- Advanced Settings -->
        <details class="advanced-settings">
          <summary>Advanced Settings</summary>
          <div class="slider-group">
            <label for="brightness-range">Brightness:</label>
            <input type="range" id="brightness-range" min="0" max="200" step="1" value="100">
          </div>
          <div class="slider-group">
            <label for="contrast-range">Contrast:</label>
            <input type="range" id="contrast-range" min="0" max="200" step="1" value="100">
          </div>
          <div class="slider-group">
            <label for="saturation-range">Saturation:</label>
            <input type="range" id="saturation-range" min="0" max="200" step="1" value="100">
          </div>
          <div class="slider-group">
            <label for="hue-range">Hue Rotate (°):</label>
            <input type="range" id="hue-range" min="0" max="360" step="1" value="0">
          </div>
          <div class="filter-group">
            <label for="filter-select">Filters:</label>
            <select id="filter-select">
              <option value="none">None</option>
              <option value="grayscale">Grayscale</option>
              <option value="sepia">Sepia</option>
              <option value="invert">Invert</option>
              <option value="blur">Blur</option>
            </select>
          </div>
        </details>

        <!-- Warning (if needed) -->
        <div class="control-group">
          <div id="warning">Warning: Image size is very large!</div>
        </div>
        <br />

        <!-- Image Format Options -->
        <div class="control-group">
          <label for="image-format">Save As:</label>
          <select id="image-format">
            <option value="jpeg">JPG</option>
            <option value="png">PNG</option>
            <option value="webp">WebP</option>
          </select>
        </div>

        <!-- Image Dimensions and File Size -->
        <div class="control-group">
          <span>Image Dimensions: <span id="download-dimensions">0 x 0 px</span></span>
          <br>
          <span>File Size: <span id="file-size">0 KB</span></span>
        </div>

        <div class="control-group">
          <button id="reset">Reset</button>
        </div>
        <!-- Save Button -->
        <div class="control-group">
          <button id="save-image">Save</button>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div id="main-content">
      <div id="image-container">
        <canvas id="image-canvas"></canvas>
        <!-- Dimension Overlay -->
        <div class="cropper-dimensions" id="cropper-dimensions"></div>
      </div>
      <!-- Drag and Drop Area -->
      <div id="drop-area">
        Drag & Drop Image or Click to Upload
      </div>
    </div>
  </div>

  <!-- Modal for image display -->
  <div id="image-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <img id="modal-image" src="" alt="Edited Image">
      <a id="download-link" href="" download="edited-image.jpg">
        <button class="download-button">Download Image</button>
      </a>
    </div>
  </div>

  <!-- Include necessary scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Include Cropper.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  <script>
    $(document).ready(function() {
      var cropper;
      var imageCanvas = document.getElementById('image-canvas');
      var ctx = imageCanvas.getContext('2d');
      var originalImage = new Image();
      var originalWidth, originalHeight;
      var flippedH = false;
      var flippedV = false;
      var filters = {
        brightness: 100,
        contrast: 100,
        saturation: 100,
        hue: 0,
        filter: 'none'
      };
      var cropperDimensions = document.getElementById('cropper-dimensions');

      // Modal elements
      var modal = document.getElementById('image-modal');
      var modalImage = document.getElementById('modal-image');
      var downloadLink = document.getElementById('download-link');
      var closeModal = document.getElementsByClassName('close')[0];

      // Drag and Drop area
      var dropArea = $('#drop-area');
      var uploadInput = $('<input type="file" accept="image/*" style="display:none">');
      $('body').append(uploadInput);

      dropArea.on('click', function() { uploadInput.click(); });
      uploadInput.on('change', function(e) { handleFiles(e.target.files); });
      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(eventName) {
        dropArea.on(eventName, function(e) {
          e.preventDefault();
          e.stopPropagation();
        });
      });
      dropArea.on('dragover', function() { dropArea.addClass('dragover'); });
      dropArea.on('dragleave drop', function() { dropArea.removeClass('dragover'); });
      dropArea.on('drop', function(e) {
        var dt = e.originalEvent.dataTransfer;
        handleFiles(dt.files);
      });

      function handleFiles(files) {
        if (files && files.length > 0) {
          var file = files[0];
          if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function() {
              $('#image-container').show();
              $('#drop-area').hide();
              originalImage.src = reader.result;
              originalImage.onload = function() {
                originalWidth = originalImage.naturalWidth;
                originalHeight = originalImage.naturalHeight;
                imageCanvas.width = originalWidth;
                imageCanvas.height = originalHeight;
                ctx.drawImage(originalImage, 0, 0);

                if (cropper) { cropper.destroy(); }
                cropper = new Cropper(imageCanvas, {
                  aspectRatio: NaN,
                  viewMode: 2,
                  ready: function () {
                    updateDimensions();
                    updateFileSize();
                    applyFilters();
                    updateCropperDimensions();
                  },
                  crop: function() {
                    updateDimensions();
                    updateFileSize();
                    updateCropperDimensions();
                  }
                });
              };
            };
            reader.readAsDataURL(file);
          } else {
            alert('Please upload a valid image file.');
          }
        }
      }

      // Crop Presets
      $('#crop-preset').on('change', function() {
        var ratio = $(this).val();
        cropper.setAspectRatio(ratio === 'NaN' ? NaN : parseFloat(ratio));
      });

      // Rotation Controls
      $('#rotate-left').on('click', function() { cropper.rotate(-90); });
      $('#rotate-right').on('click', function() { cropper.rotate(90); });
      $('#rotate-range').on('input', function() {
        var angle = $(this).val();
        cropper.rotateTo(angle);
      });

      // Flip Controls
      $('#flip-horizontal').on('click', function() {
        flippedH = !flippedH;
        cropper.scaleX(flippedH ? -1 : 1);
      });
      $('#flip-vertical').on('click', function() {
        flippedV = !flippedV;
        cropper.scaleY(flippedV ? -1 : 1);
      });

      // Width Scale Slider
      $('#wscale-range').on('input', function() {
        updateDimensions();
        updateFileSize();
      });

      // Adjust Filters
      $('#brightness-range, #contrast-range, #saturation-range, #hue-range').on('input', function() {
        filters.brightness = $('#brightness-range').val();
        filters.contrast = $('#contrast-range').val();
        filters.saturation = $('#saturation-range').val();
        filters.hue = $('#hue-range').val();
        applyFilters();
        updateFileSize();
      });
      $('#filter-select').on('change', function() {
        filters.filter = $(this).val();
        applyFilters();
        updateFileSize();
      });

      // Update file size when image format is changed
      $('#image-format').on('change', function () {
        updateFileSize();
      });

      // Reset Button
      $('#reset').on('click', function() { resetImage(); });

      // Save Image and show in modal
      $('#save-image').on('click', function() {
        var format = $('#image-format').val();
        var canvas = getCroppedCanvasWithFilters();
        var quality = (format === 'jpeg') ? 0.8 : 0.9; // Adjust quality for different formats
        var compressedImage = canvas.toDataURL('image/' + format, quality);
        modalImage.src = compressedImage;
        downloadLink.href = compressedImage;
        downloadLink.download = `edited-image.${format}`;
        modal.style.display = 'flex';
      });

      closeModal.onclick = function() { modal.style.display = 'none'; };
      window.onclick = function(event) { if (event.target == modal) { modal.style.display = 'none'; } };

      // Helper Functions
      function applyFilters() {
        var filterString = '';
        switch (filters.filter) {
          case 'grayscale': filterString += 'grayscale(100%) '; break;
          case 'sepia': filterString += 'sepia(100%) '; break;
          case 'invert': filterString += 'invert(100%) '; break;
          case 'blur': filterString += 'blur(5px) '; break;
          default: break;
        }
        filterString += `brightness(${filters.brightness}%) contrast(${filters.contrast}%) saturate(${filters.saturation}%) hue-rotate(${filters.hue}deg)`;
        var cropperCanvasImage = document.querySelector('.cropper-canvas img');
        var cropperPreviewImage = document.querySelector('.cropper-preview img, .cropper-view-box img');
        if (cropperCanvasImage) { cropperCanvasImage.style.filter = filterString; }
        if (cropperPreviewImage) { cropperPreviewImage.style.filter = filterString; }
      }

      function updateDimensions() {
        var cropData = cropper.getData(true);
        var wscale = $('#wscale-range').val();
        var scaledWidth = Math.round(cropData.width * (wscale / 100));
        var scaledHeight = Math.round(cropData.height * (wscale / 100));
        $('#download-dimensions').text(scaledWidth + ' x ' + scaledHeight + ' px');
      }

      function getCroppedCanvasWithFilters() {
        var wscale = $('#wscale-range').val();
        var canvas = cropper.getCroppedCanvas({
          width: cropper.getData(true).width * (wscale / 100),
          height: cropper.getData(true).height * (wscale / 100)
        });
        var filterString = '';
        switch (filters.filter) {
          case 'grayscale': filterString += 'grayscale(100%) '; break;
          case 'sepia': filterString += 'sepia(100%) '; break;
          case 'invert': filterString += 'invert(100%) '; break;
          case 'blur': filterString += 'blur(5px) '; break;
          default: break;
        }
        filterString += `brightness(${filters.brightness}%) contrast(${filters.contrast}%) saturate(${filters.saturation}%) hue-rotate(${filters.hue}deg)`;
        var offscreenCanvas = document.createElement('canvas');
        offscreenCanvas.width = canvas.width;
        offscreenCanvas.height = canvas.height;
        var offscreenCtx = offscreenCanvas.getContext('2d');
        offscreenCtx.filter = filterString;
        offscreenCtx.drawImage(canvas, 0, 0);
        return offscreenCanvas;
      }

      // Updated updateFileSize function with format estimations
      function updateFileSize() {
        var canvas = getCroppedCanvasWithFilters();
        var format = $('#image-format').val();
        var quality = (format === 'jpeg') ? 0.8 : 0.9; // Adjust quality as needed
        var compressedImage = canvas.toDataURL('image/' + format, quality);
        
        // Base estimation from dataURL length (accounting for base64 encoding)
        var fileSizeInBytes = compressedImage.length * (3 / 4);
        var fileSizeInKB = fileSizeInBytes / 1024;
        
        // Adjust estimation based on format
        if (format === 'png') {
          // PNG files are generally larger (roughly 2x compared to JPEG for similar images)
          fileSizeInKB *= 2;
        } else if (format === 'webp') {
          // WebP is often about 20% smaller than JPEG
          fileSizeInKB *= 0.8;
        }
        
        $('#file-size').text(fileSizeInKB.toFixed(2) + ' KB');
        $('#warning').hide();
      }

      function resetImage() {
        if (cropper) { cropper.destroy(); }
        ctx.clearRect(0, 0, imageCanvas.width, imageCanvas.height);
        ctx.drawImage(originalImage, 0, 0);
        flippedH = false;
        flippedV = false;
        $('#rotate-range').val(0);
        $('#wscale-range').val(100);
        $('#brightness-range').val(100);
        $('#contrast-range').val(100);
        $('#saturation-range').val(100);
        $('#hue-range').val(0);
        $('#filter-select').val('none');
        filters = { brightness: 100, contrast: 100, saturation: 100, hue: 0, filter: 'none' };
        cropper = new Cropper(imageCanvas, {
          aspectRatio: NaN,
          viewMode: 2,
          ready: function () {
            updateDimensions();
            updateFileSize();
            applyFilters();
            updateCropperDimensions();
          },
          crop: function() {
            updateDimensions();
            updateFileSize();
            updateCropperDimensions();
          }
        });
      }

      function updateCropperDimensions() {
        var cropData = cropper.getData(true);
        var width = Math.round(cropData.width);
        var height = Math.round(cropData.height);
        cropperDimensions.innerText = width + ' x ' + height + ' px';
        var cropBoxData = cropper.getCropBoxData();
        cropperDimensions.style.left = cropBoxData.left + 'px';
        cropperDimensions.style.top = cropBoxData.top + 'px';
      }
    });
  </script>
</body>
</html>
