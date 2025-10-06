<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Professional Email Builder – Drag & Drop Template Creator</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Compose polished marketing emails with drag-and-drop layouts, live previews, and responsive testing inside the BREN7 Professional Email Builder.">
  <meta name="keywords" content="email builder, drag and drop email editor, marketing template creator, responsive email preview, newsletter design tool">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Professional Email Builder – Drag & Drop Template Creator">
  <meta property="og:description" content="Build campaign-ready email templates with live previews and quick exports using the Professional Email Builder by BREN7.">
  <meta property="og:url" content="https://bren7.com/apps/professional-email-builder.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Professional Email Builder – Drag & Drop Template Creator">
  <meta name="twitter:description" content="Design responsive marketing emails quickly with BREN7's Professional Email Builder.">
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


  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Summernote CSS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <style>
    :root {
      /* Core page colors (static) */
      --primary-color: #5b21b6;
      --primary-hover: #4c1d95;
      --primary-light: #7c3aed;

      /* Defaults for page text/background */
      --bg-color: #ffffff;
      --text-color: #111827;

      --gray-900: #1f2937;
      --gray-800: #374151;
      --gray-700: #4b5563;
      --gray-600: #6b7280;
      --gray-500: #9ca3af;
      --gray-400: #d1d5db;
      --gray-300: #e5e7eb;
      --gray-200: #f3f4f6;
      --gray-100: #f9fafb;
      --gray-50: #f8fafc;

      --white: #ffffff;

      /* Shadows and radii */
      --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
      --radius: 0.5rem;
      --radius-lg: 0.75rem;
      --transition: all 0.2s ease;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: var(--gray-100);
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      color: var(--text-color);
      min-height: 100vh;
    }

    /* Top Navigation */
    .navbar-top {
      background: var(--white);
      border-bottom: 1px solid var(--gray-200);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: var(--shadow-sm);
    }

    .navbar-brand {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-color);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .navbar-brand:hover {
      color: var(--primary-hover);
    }

    /* Main Container */
    .main-wrapper {
      max-width: 1600px;
      margin: 0 auto;
      padding: 2rem 1rem;
    }

    .content-grid {
      display: grid;
      grid-template-columns: 380px 1fr;
      gap: 2rem;
      align-items: start;
    }

    /* Sidebar */
    .sidebar {
      position: sticky;
      top: 100px;
      max-height: calc(100vh - 120px);
      overflow-y: auto;
    }

    .sidebar-card {
      background: var(--white);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-md);
      margin-bottom: 1.5rem;
      overflow: hidden;
      transition: var(--transition);
    }

    .sidebar-card:hover {
      box-shadow: var(--shadow-lg);
    }

    .card-header-custom {
      background: var(--gray-50);
      border-bottom: 2px solid var(--gray-200);
      padding: 1rem 1.5rem;
      font-weight: 600;
      color: var(--gray-800);
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .card-body-custom {
      padding: 1.5rem;
    }

    /* Main Content */
    .main-content {
      background: var(--white);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-lg);
      overflow: hidden;
    }

    .content-header {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      color: var(--white);
      padding: 2rem;
    }

    .content-header h1 {
      margin: 0 0 0.5rem 0;
      font-size: 2rem;
      font-weight: 700;
    }

    .content-header p {
      margin: 0;
      opacity: 0.9;
      font-size: 1.125rem;
    }

    .content-body {
      padding: 2rem;
    }

    /* Enhanced Form Controls */
    .form-label {
      font-weight: 600;
      color: var(--gray-700);
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
    }

    .form-control,
    .form-select {
      border: 2px solid var(--gray-300);
      border-radius: var(--radius);
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      transition: var(--transition);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(91, 33, 182, 0.1);
      outline: none;
    }

    /* Enhanced Buttons */
    .btn {
      border-radius: var(--radius);
      padding: 0.625rem 1.25rem;
      font-weight: 600;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.875rem;
      border: none;
    }

    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      color: var(--white);
    }

    .btn-primary-custom:hover {
      background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
      color: var(--white);
    }

    .btn-outline-custom {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
      background: transparent;
    }

    .btn-outline-custom:hover {
      background: var(--primary-color);
      color: var(--white);
      transform: translateY(-1px);
    }

    .btn-secondary-custom {
      background: var(--gray-200);
      color: var(--gray-700);
    }

    .btn-secondary-custom:hover {
      background: var(--gray-300);
      color: var(--gray-800);
    }

    .btn-icon {
      width: 40px;
      height: 40px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: var(--radius);
    }

    /* Template Selection */
    .template-select-wrapper {
      position: relative;
    }

    .template-preview {
      margin-top: 1rem;
      padding: 1rem;
      background: var(--gray-50);
      border-radius: var(--radius);
      font-size: 0.875rem;
      color: var(--gray-600);
      text-align: center;
      min-height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Upload Areas */
    .upload-area {
      border: 2px dashed var(--gray-300);
      border-radius: var(--radius);
      padding: 2rem;
      text-align: center;
      transition: var(--transition);
      background: var(--gray-50);
      position: relative; /* for absolute file input */
    }

    .upload-area:hover {
      border-color: var(--primary-color);
      background: rgba(91, 33, 182, 0.02);
    }

    .upload-area.dragover {
      border-color: var(--primary-color);
      background: rgba(91, 33, 182, 0.05);
      transform: scale(1.02);
    }

    .upload-icon {
      font-size: 2.5rem;
      color: var(--gray-400);
      margin-bottom: 1rem;
    }

    .upload-text {
      color: var(--gray-600);
      margin-bottom: 0.5rem;
    }

    .upload-hint {
      font-size: 0.75rem;
      color: var(--gray-500);
    }

    .image-preview {
      margin-top: 1rem;
      position: relative;
      display: inline-block;
    }

    .image-preview img {
      max-width: 100%;
      height: auto;
      border-radius: var(--radius);
      box-shadow: var(--shadow-md);
    }

    .remove-image {
      position: absolute;
      top: -0.5rem;
      right: -0.5rem;
      background: #ef4444;
      color: var(--white);
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: var(--shadow-md);
    }

    .remove-image:hover {
      background: #dc2626;
      transform: scale(1.1);
    }

    /* Device Preview Buttons */
    .device-preview-group {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-bottom: 2rem;
    }

    .device-preview-btn {
      padding: 0.625rem 1.25rem;
      border-radius: var(--radius);
      border: 2px solid var(--gray-300);
      background: var(--white);
      cursor: pointer;
      transition: var(--transition);
      font-weight: 500;
      font-size: 0.875rem;
    }

    .device-preview-btn:hover {
      border-color: var(--gray-400);
      background: var(--gray-50);
    }

    .device-preview-btn.active {
      background: var(--primary-color);
      color: var(--white);
      border-color: var(--primary-color);
    }

    /* Preview Frame */
    .preview-container {
      background: var(--gray-100);
      padding: 2rem;
      border-radius: var(--radius);
      display: flex;
      justify-content: center;
    }

    .preview-device {
      transition: var(--transition);
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow-xl);
      overflow: hidden;
    }

    .preview-device.desktop {
      width: 100%;
      max-width: 900px;
    }

    .preview-device.tablet {
      width: 768px;
      max-width: 100%;
    }

    .preview-device.mobile {
      width: 375px;
      max-width: 100%;
    }

    /* Enhanced Editor */
    .editor-wrapper {
      border: 2px solid var(--gray-300);
      border-radius: var(--radius-lg);
      overflow: hidden;
      transition: var(--transition);
    }

    .editor-wrapper:focus-within {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(91, 33, 182, 0.1);
    }

    .note-editor.note-frame {
      border: none !important;
    }

    .note-toolbar {
      background: var(--gray-50) !important;
      border-bottom: 1px solid var(--gray-300) !important;
    }

    /* Theme Selector (inside settings) */
    .theme-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.75rem;
      margin-bottom: 1rem;
    }

    .theme-option {
      border: 2px solid var(--gray-300);
      border-radius: var(--radius);
      padding: 0.75rem;
      cursor: pointer;
      transition: var(--transition);
      text-align: center;
    }

    .theme-option:hover {
      border-color: var(--primary-color);
      transform: translateY(-2px);
    }

    .theme-option.active {
      border-color: var(--primary-color);
      background: rgba(91, 33, 182, 0.05);
    }

    .theme-preview {
      width: 100%;
      height: 40px;
      border-radius: 0.25rem;
      margin-bottom: 0.5rem;
    }

    .theme-name {
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--gray-700);
    }

    .color-input-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-top: 1rem;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
      .content-grid {
        grid-template-columns: 340px 1fr;
      }
    }

    @media (max-width: 992px) {
      .content-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }

      .sidebar {
        position: static;
        max-height: none;
      }
    }

    @media (max-width: 768px) {
      .main-wrapper {
        padding: 1rem 0.5rem;
      }

      .content-header {
        padding: 1.5rem;
      }

      .content-header h1 {
        font-size: 1.5rem;
      }

      .content-body {
        padding: 1.5rem;
      }

      .device-preview-group {
        flex-wrap: wrap;
      }
    }

    /* Loading State */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      display: none;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid var(--white);
      border-top-color: var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
  </div>

  <!-- Toast Container -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- Top Navigation -->
  <nav class="navbar-top">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between">
        <a href="#" class="navbar-brand">
          <i class="fas fa-envelope"></i>
          Professional Email Builder
        </a>
        <div class="d-flex gap-2">
          <button class="btn btn-secondary-custom" onclick="showHelp()">
            <i class="fas fa-question-circle"></i>
            <span class="d-none d-md-inline">Help</span>
          </button>
          <!-- About button removed -->
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-wrapper">
    <div class="content-grid">
      <!-- Sidebar -->
      <aside class="sidebar">
        <!-- Quick Actions -->
        <div class="sidebar-card">
          <div class="card-header-custom">
            <i class="fas fa-bolt me-2"></i>Quick Actions
          </div>
          <div class="card-body-custom">
            <button class="btn btn-outline-custom w-100 mb-2" onclick="openSettings()">
              <i class="fas fa-cog"></i>Settings
            </button>
            <button class="btn btn-outline-custom w-100 mb-2" onclick="showPreview()">
              <i class="fas fa-eye"></i>Preview
            </button>
            <button class="btn btn-primary-custom w-100 mb-2" onclick="saveDraft()">
              <i class="fas fa-save"></i>Save Draft
            </button>
            <button class="btn btn-primary-custom w-100" onclick="sendTestEmail()">
              <i class="fas fa-paper-plane"></i>Send Test
            </button>
          </div>
        </div>

        <!-- Email Templates -->
        <div class="sidebar-card">
          <div class="card-header-custom">
            <i class="fas fa-layer-group me-2"></i>Email Templates
          </div>
          <div class="card-body-custom">
            <div class="template-select-wrapper">
              <select id="templateSelect" class="form-select mb-3" onchange="previewTemplate()">
                <option value="scratch" selected>Start from Scratch</option>
                <option value="welcome">Welcome Email</option>
                <option value="thankyou">Thank You Email</option>
                <option value="confirmation">Confirmation Email</option>
                <option value="passwordreset">Password Reset</option>
                <option value="newsletter">Newsletter</option>
                <option value="appointment">Appointment Reminder</option>
                <option value="donation">Donation Request</option>
                <option value="invitation">Event Invitation</option>
                <option value="feedback">Feedback Request</option>
                <option value="announcement">Announcement</option>
              </select>
              <div id="templatePreview" class="template-preview">
                <span>Select a template to see preview</span>
              </div>
              <button id="loadTemplateBtn" class="btn btn-outline-custom w-100 mt-3">
                <i class="fas fa-download"></i>Load Template
              </button>
            </div>
          </div>
        </div>

        <!-- "Open Settings" card removed -->
      </aside>

      <!-- Main Content Area -->
      <main class="main-content">
        <div class="content-header">
          <h1><i class="fas fa-envelope me-3"></i>Create Your Email</h1>
          <p>Design beautiful, responsive emails with ease</p>
        </div>

        <div class="content-body">
          <form id="emailForm">
            <!-- Email Details -->
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="recipientEmail" class="form-label">
                  <i class="fas fa-user me-2"></i>Recipient Email
                </label>
                <input
                  type="email"
                  class="form-control"
                  id="recipientEmail"
                  placeholder="recipient@example.com"
                  required
                />
              </div>
              <div class="col-md-6">
                <label for="subject" class="form-label">
                  <i class="fas fa-heading me-2"></i>Subject Line
                </label>
                <input
                  type="text"
                  class="form-control"
                  id="subject"
                  placeholder="Enter compelling subject line"
                  required
                />
              </div>
            </div>

            <!-- Rich Text Editor -->
            <div class="mb-4">
              <label class="form-label">
                <i class="fas fa-edit me-2"></i>Email Content
              </label>
              <div class="editor-wrapper">
                <div id="summernote"></div>
              </div>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <!-- Preview Modal -->
  <div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-eye me-2"></i>Email Preview
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="device-preview-group">
            <button class="device-preview-btn active" data-device="desktop" onclick="switchDevice('desktop')">
              <i class="fas fa-desktop me-1"></i>Desktop
            </button>
            <button class="device-preview-btn" data-device="tablet" onclick="switchDevice('tablet')">
              <i class="fas fa-tablet-alt me-1"></i>Tablet
            </button>
            <button class="device-preview-btn" data-device="mobile" onclick="switchDevice('mobile')">
              <i class="fas fa-mobile-alt me-1"></i>Mobile
            </button>
          </div>
          <div class="preview-container">
            <div class="preview-device desktop">
              <iframe id="previewIframe" style="width: 100%; min-height: 600px; border: none;"></iframe>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Settings Modal -->
  <div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <form id="emailSettingsForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-cog me-2"></i>Email Settings
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <div class="row gx-4">
              <!-- Left Column: Email Configuration & Brand Assets & Color Theme -->
              <div class="col-lg-6">
                <!-- EMAIL CONFIGURATION -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-2">
                    <i class="fas fa-envelope-open me-2"></i>Email Configuration
                  </h6>
                  <div class="row g-3">
                    <div class="col-12">
                      <label for="fromName" class="form-label">From Name</label>
                      <input type="text" class="form-control" id="fromName" placeholder="Your Company Name" />
                    </div>
                    <div class="col-12">
                      <label for="fromAddress" class="form-label">From Email</label>
                      <input type="email" class="form-control" id="fromAddress" placeholder="no-reply@yourcompany.com" />
                    </div>
                    <div class="col-12">
                      <label for="replyTo" class="form-label">Reply-To</label>
                      <input type="email" class="form-control" id="replyTo" placeholder="support@yourcompany.com" />
                    </div>
                    <div class="col-12">
                      <label for="cc" class="form-label">CC (comma-separated)</label>
                      <input type="text" class="form-control" id="cc" placeholder="cc@yourcompany.com" />
                    </div>
                  </div>
                </div>

                <!-- BRAND ASSETS -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-2">
                    <i class="fas fa-palette me-2"></i>Brand Assets
                  </h6>
                  <div class="row g-3">
                    <div class="col-6">
                      <label class="form-label">Header Banner</label>
                      <div class="upload-area position-relative" id="bannerUploadArea">
                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                        <p class="upload-text">Drag &amp; drop banner or click to browse</p>
                        <small class="upload-hint">600×200px recommended</small>
                        <input
                          type="file"
                          id="bannerFile"
                          accept="image/*"
                          style="
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            opacity: 0;
                            cursor: pointer;
                          "
                        />
                      </div>
                      <div id="bannerPreview" class="image-preview d-none mt-2">
                        <img id="bannerPreviewImg" src="" alt="Banner Preview" style="max-height: 80px;" />
                        <button type="button" class="remove-image" id="removeBanner">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                    <div class="col-6">
                      <label class="form-label">Company Logo</label>
                      <div class="upload-area position-relative" id="logoUploadArea">
                        <i class="fas fa-image upload-icon"></i>
                        <p class="upload-text">Drag &amp; drop logo or click to browse</p>
                        <small class="upload-hint">200×80px (PNG)</small>
                        <input
                          type="file"
                          id="logoFile"
                          accept="image/*"
                          style="
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            opacity: 0;
                            cursor: pointer;
                          "
                        />
                      </div>
                      <div id="logoPreview" class="image-preview d-none mt-2">
                        <img id="logoPreviewImg" src="" alt="Logo Preview" style="max-height: 80px;" />
                        <button type="button" class="remove-image" id="removeLogo">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- COLOR THEME -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-2">
                    <i class="fas fa-palette me-2"></i>Color Theme
                  </h6>
                  <div class="theme-grid" id="themeGrid">
                    <!-- JS will inject .theme-option cards here -->
                  </div>
                  <div id="customColorOptions" class="color-input-group d-none">
                    <div>
                      <label class="form-label">Background</label>
                      <input type="color" class="form-control form-control-color" id="themeBgColor" value="#ffffff">
                    </div>
                    <div>
                      <label class="form-label">Text Color</label>
                      <input type="color" class="form-control form-control-color" id="themeTextColor" value="#000000">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column: Typography, Footer, Social Links -->
              <div class="col-lg-6">
                <!-- TYPOGRAPHY -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-2">
                    <i class="fas fa-font me-2"></i>Typography
                  </h6>
                  <div class="row g-3">
                    <div class="col-6">
                      <label for="headingFont" class="form-label">Heading Font</label>
                      <select class="form-select" id="headingFont">
                        <option value="Roboto" selected>Roboto</option>
                        <option value="Open+Sans">Open Sans</option>
                        <option value="Lato">Lato</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Merriweather">Merriweather</option>
                        <option value="Poppins">Poppins</option>
                        <option value="Oswald">Oswald</option>
                      </select>
                    </div>
                    <div class="col-6">
                      <label for="bodyFont" class="form-label">Body Font</label>
                      <select class="form-select" id="bodyFont">
                        <option value="Roboto" selected>Roboto</option>
                        <option value="Open+Sans">Open Sans</option>
                        <option value="Lato">Lato</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Merriweather">Merriweather</option>
                        <option value="Poppins">Poppins</option>
                        <option value="Oswald">Oswald</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- FOOTER CONFIGURATION -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-2">
                    <i class="fas fa-file-signature me-2"></i>Footer Configuration
                  </h6>
                  <div class="mb-3">
                    <label for="footerText" class="form-label">Footer Text</label>
                    <textarea
                      class="form-control"
                      id="footerText"
                      rows="2"
                      placeholder="1234 Main St, City, Prov • support@yourcompany.com • (123) 456-7890"
                    ></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="unsubscribeText" class="form-label">Unsubscribe Link Text</label>
                    <input
                      type="text"
                      class="form-control"
                      id="unsubscribeText"
                      placeholder="Click here to unsubscribe"
                    />
                  </div>
                  <div class="mb-3">
                    <label for="unsubscribeUrl" class="form-label">Unsubscribe URL</label>
                    <input
                      type="url"
                      class="form-control"
                      id="unsubscribeUrl"
                      placeholder="https://yourcompany.com/unsubscribe"
                    />
                  </div>
                </div>

                <!-- SOCIAL MEDIA LINKS -->
                <div>
                  <h6 class="fw-bold mb-2">
                    <i class="fas fa-share-alt me-2"></i>Social Media Links
                  </h6>
                  <div class="row g-3">
                    <div class="col-6">
                      <label for="facebookLink" class="form-label">Facebook URL</label>
                      <input
                        type="url"
                        class="form-control"
                        id="facebookLink"
                        placeholder="https://facebook.com/yourpage"
                      />
                    </div>
                    <div class="col-6">
                      <label for="twitterLink" class="form-label">Twitter URL</label>
                      <input
                        type="url"
                        class="form-control"
                        id="twitterLink"
                        placeholder="https://twitter.com/yourpage"
                      />
                    </div>
                    <div class="col-6">
                      <label for="linkedInLink" class="form-label">LinkedIn URL</label>
                      <input
                        type="url"
                        class="form-control"
                        id="linkedInLink"
                        placeholder="https://linkedin.com/company/yourcompany"
                      />
                    </div>
                    <div class="col-6">
                      <label for="instagramLink" class="form-label">Instagram URL</label>
                      <input
                        type="url"
                        class="form-control"
                        id="instagramLink"
                        placeholder="https://instagram.com/yourpage"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary-custom" id="saveSettingsBtn">
              <i class="fas fa-save me-2"></i>Save Settings
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Help Modal -->
  <div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fas fa-question-circle me-2"></i>Help & Documentation
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="accordion" id="helpAccordion">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#help1">
                  Getting Started
                </button>
              </h2>
              <div id="help1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                <div class="accordion-body">
                  <ol>
                    <li><strong>Choose a Template:</strong> Select from pre-built templates or start from scratch</li>
                    <li><strong>Configure Settings:</strong> Set up your sender information, branding, theme, fonts, and footer</li>
                    <li><strong>Create Content:</strong> Write your email using our rich text editor</li>
                    <li><strong>Preview & Test:</strong> Check how your email looks on different devices</li>
                    <li><strong>Send:</strong> Deploy your email to recipients</li>
                  </ol>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#help2">
                  Best Practices
                </button>
              </h2>
              <div id="help2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                <div class="accordion-body">
                  <ul>
                    <li><strong>Subject Lines:</strong> Keep them under 50 characters and make them compelling</li>
                    <li><strong>Content:</strong> Use clear, concise language and include a clear call-to-action</li>
                    <li><strong>Images:</strong> Optimize for web (under 1MB) and always include alt text</li>
                    <li><strong>Mobile:</strong> Test on mobile devices – 60% of emails are opened on mobile</li>
                    <li><strong>Accessibility:</strong> Use sufficient color contrast and descriptive link text</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#help3">
                  Troubleshooting
                </button>
              </h2>
              <div id="help3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                <div class="accordion-body">
                  <ul>
                    <li><strong>Images not loading:</strong> Ensure images are properly uploaded and under 1MB</li>
                    <li><strong>Preview issues:</strong> Try refreshing the preview or checking your browser compatibility</li>
                    <li><strong>Template not loading:</strong> Clear your browser cache and try again</li>
                    <li><strong>Formatting problems:</strong> Use the rich text editor’s formatting tools consistently</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Summernote JS -->
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>

  <script>
    // Theme configurations
    const themeOptions = [
      {
        id: 'default',
        name: 'Default',
        primary: '#5b21b6',
        primaryLight: '#7c3aed',
        primaryHover: '#4c1d95',
        bg: '#ffffff',
        text: '#000000',
        preview: 'linear-gradient(to bottom, #ffffff, #f3f4f6)'
      },
      {
        id: 'modern',
        name: 'Modern Blue',
        primary: '#3b82f6',
        primaryLight: '#93c5fd',
        primaryHover: '#1e40af',
        bg: '#f0f4ff',
        text: '#1f2937',
        preview: 'linear-gradient(to bottom, #3b82f6, #1e40af)'
      },
      {
        id: 'nature',
        name: 'Nature Green',
        primary: '#10b981',
        primaryLight: '#6ee7b7',
        primaryHover: '#059669',
        bg: '#f0fff4',
        text: '#1f2937',
        preview: 'linear-gradient(to bottom, #10b981, #059669)'
      },
      {
        id: 'sunset',
        name: 'Sunset Orange',
        primary: '#f59e0b',
        primaryLight: '#fed7aa',
        primaryHover: '#d97706',
        bg: '#fff7ed',
        text: '#1f2937',
        preview: 'linear-gradient(to bottom, #f59e0b, #dc2626)'
      },
      {
        id: 'professional',
        name: 'Professional Dark',
        primary: '#1f2937',
        primaryLight: '#4b5563',
        primaryHover: '#111827',
        bg: '#1f2937',
        text: '#f8fafc',
        preview: 'linear-gradient(to bottom, #1f2937, #111827)'
      },
      {
        id: 'custom',
        name: 'Custom',
        primary: '#5b21b6',
        primaryLight: '#7c3aed',
        primaryHover: '#4c1d95',
        bg: '#ffffff',
        text: '#000000',
        preview: 'linear-gradient(to bottom, #e5e7eb, #9ca3af)'
      }
    ];

    let currentTheme = 'default';
    let bannerBase64 = '';
    let logoBase64 = '';

    document.addEventListener('DOMContentLoaded', () => {
      renderThemeGrid();
      initializeFileUploads();

      // Initialize Summernote only once
      if (!$('#summernote').hasClass('note-editor')) {
        $('#summernote').summernote({
          placeholder: 'Create your email content here...',
          height: 400,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold','italic','underline','strikethrough','superscript','subscript','clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul','ol','paragraph','justifyLeft','justifyCenter','justifyRight','justifyFull']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link','picture','video','hr']],
            ['view', ['fullscreen','codeview','help']]
          ],
          fontNames: [
            'Arial','Arial Black','Comic Sans MS','Courier New','Helvetica','Impact',
            'Tahoma','Times New Roman','Verdana','Roboto'
          ],
          fontSizes: ['8','9','10','11','12','14','16','18','20','24','28','32','36','48','64']
        });
      }
    });

    function renderThemeGrid() {
      const themeGrid = document.getElementById('themeGrid');
      if (!themeGrid) return;
      themeGrid.innerHTML = themeOptions
        .map(
          (theme) => `
        <div class="theme-option ${theme.id === currentTheme ? 'active' : ''}" onclick="selectTheme('${theme.id}')">
          <div class="theme-preview" style="background: ${theme.preview}"></div>
          <div class="theme-name">${theme.name}</div>
        </div>
      `
        )
        .join('');
    }

    function selectTheme(themeId) {
      currentTheme = themeId;
      renderThemeGrid();
      if (themeId === 'custom') {
        document.getElementById('customColorOptions').classList.remove('d-none');
      } else {
        document.getElementById('customColorOptions').classList.add('d-none');
      }
      showToast('Theme selected', 'success');
    }

    function initializeFileUploads() {
      // Banner
      const bannerUploadArea = document.getElementById('bannerUploadArea');
      const bannerFileInput = document.getElementById('bannerFile');
      if (bannerUploadArea && bannerFileInput) {
        bannerUploadArea.addEventListener('dragover', handleDragOver);
        bannerUploadArea.addEventListener('dragleave', handleDragLeave);
        bannerUploadArea.addEventListener('drop', (e) => handleFileDrop(e, 'banner'));
        bannerFileInput.addEventListener('change', (e) => handleFileSelect(e, 'banner'));
      }
      // Logo
      const logoUploadArea = document.getElementById('logoUploadArea');
      const logoFileInput = document.getElementById('logoFile');
      if (logoUploadArea && logoFileInput) {
        logoUploadArea.addEventListener('dragover', handleDragOver);
        logoUploadArea.addEventListener('dragleave', handleDragLeave);
        logoUploadArea.addEventListener('drop', (e) => handleFileDrop(e, 'logo'));
        logoFileInput.addEventListener('change', (e) => handleFileSelect(e, 'logo'));
      }
      // Remove buttons
      const removeBannerBtn = document.getElementById('removeBanner');
      if (removeBannerBtn) {
        removeBannerBtn.addEventListener('click', () => removeFile('banner'));
      }
      const removeLogoBtn = document.getElementById('removeLogo');
      if (removeLogoBtn) {
        removeLogoBtn.addEventListener('click', () => removeFile('logo'));
      }
    }

    function handleDragOver(e) {
      e.preventDefault();
      e.currentTarget.classList.add('dragover');
    }

    function handleDragLeave(e) {
      e.currentTarget.classList.remove('dragover');
    }

    function handleFileDrop(e, type) {
      e.preventDefault();
      e.currentTarget.classList.remove('dragover');
      const files = e.dataTransfer.files;
      if (!files || files.length === 0) return;
      const fileInput = document.getElementById(type + 'File');
      fileInput.files = files;
      handleFileSelect({ target: fileInput }, type);
    }

    function handleFileSelect(e, type) {
      const file = e.target.files[0];
      if (!file) return;
      if (!file.type.startsWith('image/')) {
        showToast('Please select an image file', 'danger');
        return;
      }
      if (file.size > 5 * 1024 * 1024) {
        showToast('File size must be less than 5MB', 'danger');
        return;
      }
      convertFileToBase64(file, (base64) => {
        if (!base64) return;
        showFilePreview(type, base64);
        if (type === 'banner') {
          bannerBase64 = base64;
        } else {
          logoBase64 = base64;
        }
        showToast(type.charAt(0).toUpperCase() + type.slice(1) + ' uploaded successfully', 'success');
      });
    }

    function convertFileToBase64(file, callback) {
      if (!file) return callback('');
      const reader = new FileReader();
      reader.onload = (e) => callback(e.target.result);
      reader.onerror = () => {
        showToast('Error reading file', 'danger');
        callback('');
      };
      reader.readAsDataURL(file);
    }

    function showFilePreview(type, base64) {
      const previewDiv = document.getElementById(type + 'Preview');
      const previewImg = document.getElementById(type + 'PreviewImg');
      if (!previewDiv || !previewImg) return;
      previewImg.src = base64;
      previewDiv.classList.remove('d-none');
    }

    function removeFile(type) {
      const fileInput = document.getElementById(type + 'File');
      const previewDiv = document.getElementById(type + 'Preview');
      if (fileInput) fileInput.value = '';
      if (previewDiv) previewDiv.classList.add('d-none');
      if (type === 'banner') {
        bannerBase64 = '';
      } else if (type === 'logo') {
        logoBase64 = '';
      }
      showToast(type.charAt(0).toUpperCase() + type.slice(1) + ' removed', 'success');
    }

    // Email Templates
    const emailTemplates = {
      scratch: {
        subject: '',
        content: '',
        preview: 'Start with a blank canvas'
      },
      welcome: {
        subject: 'Welcome to Our Community! 🎉',
        content: `
          <div class="email-header-text">
            <h2>Welcome to Our Community!</h2>
            <p>We're thrilled to have you join us on this journey</p>
          </div>
          <div class="email-body-section">
            <h3>Get Started in 3 Easy Steps:</h3>
            <ol>
              <li><strong>Complete your profile</strong> – Add your information and preferences</li>
              <li><strong>Explore our features</strong> – Discover what we have to offer</li>
              <li><strong>Connect with our community</strong> – Join discussions and share experiences</li>
            </ol>
          </div>
          <p><a href="#">Get Started Now</a></p>
          <p>If you have any questions, don't hesitate to reach out to our support team. We're here to help!</p>
        `,
        preview: 'Welcome new users with a warm introduction'
      },
      thankyou: {
        subject: 'Thank You for Your Support 🙏',
        content: `
          <div class="email-header-text">
            <h2>Thank You!</h2>
            <p>Your support means the world to us</p>
          </div>
          <p>We wanted to take a moment to express our heartfelt gratitude for your recent contribution. Your generosity helps us continue our mission and make a positive impact.</p>
          <div class="email-quote">
            <p>"Together, we can achieve extraordinary things."</p>
          </div>
          <p>Stay tuned for updates on how your support is making a difference. We'll keep you informed about our progress and upcoming initiatives.</p>
        `,
        preview: 'Express gratitude for support or contributions'
      },
      confirmation: {
        subject: 'Confirmation: Your Request Has Been Received ✅',
        content: `
          <div class="email-header-text">
            <h2>Confirmation Received</h2>
            <p>This email confirms that we have successfully received your request.</p>
          </div>
          <ul>
            <li>Request submitted on: [Date]</li>
            <li>Reference number: [Reference]</li>
            <li>Expected processing time: 2-3 business days</li>
          </ul>
          <p>We will process your request and get back to you soon. If you have any questions, please reach out to our support team.</p>
        `,
        preview: 'Confirm receipt of requests or orders'
      },
      passwordreset: {
        subject: 'Reset Your Password 🔒',
        content: `
          <div class="email-header-text">
            <h2>Password Reset Request</h2>
            <p>We received a request to reset your password</p>
          </div>
          <p>If this was you, click the link below to reset your password:</p>
          <p><a href="#">Reset My Password</a></p>
          <p>If you didn't request this, please ignore this email.</p>
        `,
        preview: 'Password reset link and instructions'
      },
      newsletter: {
        subject: 'Weekly Newsletter: Latest Updates & Insights 📰',
        content: `
          <div class="email-header-text">
            <h2>This Week's Highlights</h2>
            <p>Stay informed with our latest news and insights</p>
          </div>
          <h3>🚀 Feature Spotlight</h3>
          <p>This week we're excited to highlight our newest feature that will transform how you work…</p>
          <h3>📅 Upcoming Events</h3>
          <ul>
            <li>Webinar: Advanced Tips & Tricks – March 15th</li>
            <li>Workshop: Best Practices Session – March 22nd</li>
          </ul>
          <h3>💡 User Success Story</h3>
          <p>See how our community member achieved amazing results using our platform…</p>
        `,
        preview: 'Weekly updates and news roundup'
      },
      appointment: {
        subject: 'Appointment Reminder: Tomorrow at 2:00 PM ⏰',
        content: `
          <div class="email-header-text">
            <h2>Appointment Reminder</h2>
            <p>Don't forget your appointment tomorrow!</p>
          </div>
          <ul>
            <li><strong>Date:</strong> Tomorrow, March 15th, 2024</li>
            <li><strong>Time:</strong> 2:00 PM – 3:00 PM</li>
            <li><strong>Location:</strong> Main Office, Conference Room A</li>
            <li><strong>With:</strong> Dr. Smith</li>
          </ul>
          <p>Please arrive 10 minutes early. If you need to reschedule, let us know as soon as possible.</p>
        `,
        preview: 'Remind clients of upcoming appointments'
      },
      donation: {
        subject: 'Help Us Make a Difference – Donation Appeal 💖',
        content: `
          <div class="email-header-text">
            <h2>Together, We Can Make a Difference</h2>
            <p>Your support can transform lives</p>
          </div>
          <p>Every day, we witness the impact of your generosity. Today, we're asking for your help to continue this important work.</p>
          <p><a href="#">Donate Now</a></p>
          <p>Thank you for your consideration. Every contribution, no matter the size, makes a real difference.</p>
        `,
        preview: 'Request donations for your cause'
      },
      invitation: {
        subject: "You're Invited: Annual Gala Event 🎊",
        content: `
          <div class="email-header-text">
            <h1>You're Invited!</h1>
            <h2>Annual Gala Celebration</h2>
          </div>
          <p>Join us for an evening of celebration, networking, and community building.</p>
          <ul>
            <li><strong>Date:</strong> Saturday, April 20th, 2024</li>
            <li><strong>Time:</strong> 6:00 PM – 11:00 PM</li>
            <li><strong>Venue:</strong> Grand Ballroom, City Center Hotel</li>
            <li><strong>Dress Code:</strong> Formal Attire</li>
          </ul>
          <p><a href="#">RSVP Now</a></p>
          <p>Please RSVP by April 10th as seating is limited.</p>
        `,
        preview: 'Invite guests to your special event'
      },
      feedback: {
        subject: 'We Value Your Feedback – Quick Survey 📝',
        content: `
          <div class="email-header-text">
            <h2>Your Opinion Matters</h2>
            <p>Help us improve by sharing your thoughts</p>
          </div>
          <p>This brief survey takes only 2–3 minutes and covers:</p>
          <ul>
            <li>Overall satisfaction</li>
            <li>Feature usage and preferences</li>
            <li>Suggestions for improvement</li>
          </ul>
          <p><a href="#">Take Survey</a></p>
          <p>As a thank-you, participants will be entered into a drawing for a $50 gift card.</p>
        `,
        preview: 'Request feedback from your audience'
      },
      announcement: {
        subject: 'Important Announcement: Company Updates 📢',
        content: `
          <div class="email-header-text">
            <h2>Important Announcement</h2>
            <p>Please read this important update</p>
          </div>
          <p>We have some updates to share regarding policy changes, system upgrades, and new team members.</p>
          <ul>
            <li><strong>Policy:</strong> New remote work guidelines effective immediately</li>
            <li><strong>System Upgrade:</strong> Scheduled maintenance this weekend</li>
            <li><strong>Team Changes:</strong> Welcome new team members next week</li>
          </ul>
          <p>If you have any questions, please reach out to HR or your manager.</p>
        `,
        preview: 'Share important company news'
      }
    };

    document.getElementById('loadTemplateBtn').addEventListener('click', loadTemplate);

    function previewTemplate() {
      const selectedTemplate = document.getElementById('templateSelect').value;
      const template = emailTemplates[selectedTemplate];
      const previewDiv = document.getElementById('templatePreview');
      if (template && template.preview) {
        previewDiv.innerHTML = `<span style="color: var(--gray-700)">${template.preview}</span>`;
      }
    }

    function loadTemplate() {
      const selectedKey = document.getElementById('templateSelect').value;
      const template = emailTemplates[selectedKey];
      if (!template) {
        showToast('Template not found', 'danger');
        return;
      }
      document.getElementById('subject').value = template.subject || '';
      $('#summernote').summernote('code', template.content || '');
      showToast('Template loaded successfully', 'success');
    }

    // Settings form submission
    document.getElementById('emailSettingsForm').addEventListener('submit', function(event) {
      event.preventDefault();
      showToast('Settings saved successfully', 'success');
      const settingsModal = bootstrap.Modal.getInstance(document.getElementById('settingsModal'));
      settingsModal.hide();
    });

    // Preview functionality
    function showPreview() {
      const emailHtml = $('#summernote').summernote('code');

      // Footer and social info
      let footerText = document.getElementById('footerText').value || '';
      const unsubscribeText = document.getElementById('unsubscribeText').value.trim();
      const unsubscribeUrl = document.getElementById('unsubscribeUrl').value.trim();
      const facebookLink = document.getElementById('facebookLink').value.trim();
      const twitterLink = document.getElementById('twitterLink').value.trim();
      const linkedInLink = document.getElementById('linkedInLink').value.trim();
      const instagramLink = document.getElementById('instagramLink').value.trim();

      // Append © year if absent
      const currentYear = new Date().getFullYear();
      if (!footerText.includes('©')) {
        footerText = footerText.length ? `${footerText} • © ${currentYear}` : `© ${currentYear}`;
      }

      // Build unsubscribe block only if both text and URL exist
      let unsubscribeHtml = '';
      if (unsubscribeText && unsubscribeUrl) {
        unsubscribeHtml = `<div class="unsubscribe"><a href="${escapeHtml(unsubscribeUrl)}">${escapeHtml(unsubscribeText)}</a></div>`;
      }

      // Social links
      let socialHtml = '<div class="footer-icons">';
      if (facebookLink) {
        socialHtml += `<a href="${escapeHtml(facebookLink)}" target="_blank"><i class="fab fa-facebook"></i></a>`;
      }
      if (twitterLink) {
        socialHtml += `<a href="${escapeHtml(twitterLink)}" target="_blank"><i class="fab fa-twitter"></i></a>`;
      }
      if (linkedInLink) {
        socialHtml += `<a href="${escapeHtml(linkedInLink)}" target="_blank"><i class="fab fa-linkedin"></i></a>`;
      }
      if (instagramLink) {
        socialHtml += `<a href="${escapeHtml(instagramLink)}" target="_blank"><i class="fab fa-instagram"></i></a>`;
      }
      socialHtml += "</div>";

      // Theme for preview (only for iframe, not page)
      let selectedTheme = themeOptions.find(t => t.id === currentTheme);
      let themeBg = selectedTheme.bg;
      let themeText = selectedTheme.text;
      if (currentTheme === 'custom') {
        themeBg = document.getElementById('themeBgColor').value || '#ffffff';
        themeText = document.getElementById('themeTextColor').value || '#000000';
      }

      // Fonts
      const headingFont = document.getElementById('headingFont').value || 'Roboto';
      const bodyFont = document.getElementById('bodyFont').value || 'Roboto';

      // Build header HTML
      let headerHtml = '<div class="header-banner">';
      if (bannerBase64) {
        headerHtml += `<img src="${bannerBase64}" alt="Header Banner" />`;
      }
      if (logoBase64) {
        headerHtml += `<div class="company-logo"><img src="${logoBase64}" alt="Company Logo" /></div>`;
      }
      headerHtml += "</div>";

      // Combine footer
      const footerCombined = `
        <hr class="footer-separator" />
        <div class="footer-content">
          <div class="footer-text">${escapeHtml(footerText)}</div>
          ${unsubscribeHtml}
          ${socialHtml}
        </div>
      `;

      // Full HTML document for preview
      const fullHtml = `
        <!DOCTYPE html>
        <html>
          <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <title>Email Preview</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=${headingFont.replace(/ /g,'+')}:wght@400;700&family=${bodyFont.replace(/ /g,'+')}:wght@400;700&display=swap" rel="stylesheet">
            <style>
              :root {
                --bg-color: ${themeBg};
                --text-color: ${themeText};
                --heading-font: '${headingFont.replace('+', ' ')}', sans-serif;
                --body-font: '${bodyFont.replace('+', ' ')}', sans-serif;
              }
              body {
                background-color: var(--bg-color);
                color: var(--text-color);
                margin: 20px;
                font-family: var(--body-font);
                line-height: 1.6;
              }
              .email-body h1, .email-body h2, .email-body h3, .email-body h4, .email-body h5, .email-body h6 {
                font-family: var(--heading-font);
                margin-top: 0;
                margin-bottom: 1rem;
              }
              .header-banner {
                text-align: center;
                margin-bottom: 20px;
              }
              .header-banner img {
                max-width: 100%;
                height: auto;
              }
              .company-logo img {
                max-width: 200px;
                height: auto;
                margin-top: 10px;
              }
              .footer-separator {
                border-color: var(--text-color);
                margin-top: 30px;
                margin-bottom: 15px;
                border-width: 1px 0 0 0;
                border-style: solid;
                opacity: 0.3;
              }
              .footer-content {
                font-size: 12px;
                text-align: center;
                color: var(--text-color);
                opacity: 0.8;
              }
              .footer-text {
                margin-bottom: 10px;
                line-height: 1.4;
              }
              .unsubscribe {
                margin-bottom: 10px;
              }
              .unsubscribe a {
                color: var(--text-color);
                font-size: 11px;
                text-decoration: underline;
              }
              .footer-icons {
                margin-top: 10px;
              }
              .footer-icons a {
                margin: 0 8px;
                color: var(--text-color);
                text-decoration: none;
                font-size: 18px;
                opacity: 0.7;
                transition: opacity 0.2s;
              }
              .footer-icons a:hover {
                opacity: 1;
              }
              .email-body {
                max-width: 600px;
                margin: 0 auto;
              }
              .email-body a {
                color: #3b82f6;
                text-decoration: none;
              }
              .email-body a:hover {
                text-decoration: underline;
              }
              @media (max-width: 600px) {
                body {
                  margin: 10px;
                }
                .header-banner img {
                  max-width: 100%;
                }
                .company-logo img {
                  max-width: 150px;
                }
                .footer-icons a {
                  margin: 0 5px;
                  font-size: 16px;
                }
              }
            </style>
          </head>
          <body>
            ${headerHtml}
            <div class="email-body">${emailHtml}</div>
            ${footerCombined}
          </body>
        </html>
      `;

      const iframe = document.getElementById('previewIframe');
      iframe.srcdoc = fullHtml;

      // Reset to desktop view
      document.querySelectorAll('.device-preview-btn').forEach((btn) => {
        btn.classList.remove('active');
      });
      document.querySelector('.device-preview-btn[data-device="desktop"]').classList.add('active');
      document.querySelector('.preview-device').className = 'preview-device desktop';

      const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
      previewModal.show();
    }

    function switchDevice(device) {
      document.querySelectorAll('.device-preview-btn').forEach((btn) => {
        btn.classList.remove('active');
      });
      document.querySelector(`.device-preview-btn[data-device="${device}"]`).classList.add('active');
      const previewDevice = document.querySelector('.preview-device');
      previewDevice.className = `preview-device ${device}`;
    }

    function openSettings() {
      const settingsModal = new bootstrap.Modal(document.getElementById('settingsModal'));
      settingsModal.show();
    }

    function showHelp() {
      const helpModal = new bootstrap.Modal(document.getElementById('helpModal'));
      helpModal.show();
    }

    function saveDraft() {
      showLoading();
      setTimeout(() => {
        hideLoading();
        showToast('Draft saved successfully', 'success');
      }, 1000);
    }

    function sendTestEmail() {
      const recipient = document.getElementById('recipientEmail').value.trim();
      const subject = document.getElementById('subject').value.trim();
      if (!recipient || !subject) {
        showToast('Please fill in recipient email and subject', 'warning');
        return;
      }
      showLoading();
      setTimeout(() => {
        hideLoading();
        showToast('Test email sent successfully!', 'success');
      }, 1500);
    }

    function showToast(message, type = 'success') {
      const toastContainer = document.getElementById('toastContainer');
      const toastId = 'toast-' + Date.now();
      const icon = type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'exclamation-circle' : 'info-circle';
      const toastHtml = `
        <div class="toast align-items-center text-bg-${type} border-0" role="alert" id="${toastId}">
          <div class="d-flex">
            <div class="toast-body">
              <i class="fas fa-${icon} me-2"></i>
              ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
          </div>
        </div>
      `;
      toastContainer.insertAdjacentHTML('beforeend', toastHtml);
      const toastElement = document.getElementById(toastId);
      const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
      toast.show();
      toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
    }

    function escapeHtml(str) {
      return (str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function showLoading() {
      document.getElementById('loadingOverlay').style.display = 'flex';
    }

    function hideLoading() {
      document.getElementById('loadingOverlay').style.display = 'none';
    }
  </script>
</body>
</html>
