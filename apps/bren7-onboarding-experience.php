<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BREN7 CMS – Enhanced Onboarding Experience</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Explore interactive onboarding flows, task automation, and client resources inside the BREN7 CMS Enhanced Onboarding Experience prototype by BREN7.">
  <meta name="keywords" content="BREN7 onboarding, client onboarding dashboard, cms training journey, implementation planner, agency workflow">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="BREN7 CMS – Enhanced Onboarding Experience">
  <meta property="og:description" content="Preview the guided onboarding portal concept for BREN7 CMS featuring tasks, resources, and client insights.">
  <meta property="og:url" content="https://bren7.com/apps/bren7-onboarding-experience.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="BREN7 CMS – Enhanced Onboarding Experience">
  <meta name="twitter:description" content="Tour the BREN7 CMS onboarding portal concept with guided tasks and resources by BREN7.">
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
    :root {
      --bg: #0f1216;
      --panel: #151a20;
      --muted: #9aa4b2;
      --text: #e6e9ee;
      --accent: #5aa7ff;
      --accent-2: #ff75c3;
      --danger: #ff5a7a;
      --warning: #ffb347;
      --good: #6dd3ff;
      --success: #4ade80;
      --divider: #232a34;
      --shadow: 0 10px 30px rgba(0,0,0,.35);
      --radius: 16px;
    }

    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      background: radial-gradient(1200px 800px at 10% -10%, rgba(90,167,255,.15), transparent),
                  radial-gradient(900px 700px at 110% 10%, rgba(255,117,195,.12), transparent),
                  var(--bg);
      color: var(--text);
      font: 15px/1.5 system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
    }

    .app {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 20px;
      padding: 20px;
      max-width: 1400px;
      margin: 0 auto;
      min-height: 100vh;
    }

    header.app-header {
      grid-column: 1 / -1;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(180deg, rgba(90,167,255,.08), rgba(255,117,195,.0));
      border: 1px solid var(--divider);
      border-radius: var(--radius);
      padding: 16px 20px;
      box-shadow: var(--shadow);
      position: sticky;
      top: 20px;
      z-index: 100;
    }

    .brand {
      display: flex;
      gap: 12px;
      align-items: center;
      font-weight: 700;
      letter-spacing: .3px;
    }
    .brand .logo {
      width: 36px; height: 36px;
      background: conic-gradient(from 210deg, var(--accent), var(--accent-2));
      border-radius: 10px;
      box-shadow: 0 6px 14px rgba(90,167,255,.25), inset 0 0 0 1px rgba(255,255,255,.05);
    }

    .completion-banner {
      background: linear-gradient(90deg, var(--success), #22c55e);
      color: white;
      padding: 8px 16px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      display: none;
      align-items: center;
      gap: 8px;
    }

    .header-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .time-estimate { 
      font-size: 13px; 
      color: var(--muted); 
      display: flex; 
      align-items: center; 
      gap: 6px;
      padding: 4px 8px;
      background: rgba(255,255,255,.05);
      border-radius: 6px;
    }

    button, .button {
      appearance: none;
      border: 1px solid var(--divider);
      background: linear-gradient(180deg, #1a2028, #14181e);
      color: var(--text);
      padding: 8px 12px;
      border-radius: 10px;
      cursor: pointer;
      transition: all .2s ease;
      box-shadow: 0 2px 0 rgba(0,0,0,.25);
      font-weight: 600;
      position: relative;
      overflow: hidden;
    }
    button:hover { 
      border-color: #2e3744; 
      box-shadow: 0 4px 14px rgba(0,0,0,.25); 
      transform: translateY(-1px);
    }
    button:active { transform: translateY(1px); }
    button:disabled {
      opacity: .5;
      cursor: not-allowed;
      transform: none !important;
    }

    .button-primary {
      background: linear-gradient(180deg, rgba(90,167,255,.35), rgba(90,167,255,.15));
      border: 1px solid rgba(90,167,255,.45);
      color: white;
      text-shadow: 0 1px 0 rgba(0,0,0,.35);
    }
    .button-success {
      background: linear-gradient(180deg, rgba(74,222,128,.35), rgba(74,222,128,.15));
      border: 1px solid rgba(74,222,128,.45);
      color: white;
    }
    .button-ghost { background: transparent; }

    /* Pulse animation for CTAs */
    @keyframes pulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(90,167,255,.4); }
      50% { box-shadow: 0 0 0 8px rgba(90,167,255,0); }
    }
    .button-pulse { animation: pulse 2s infinite; }

    /* Sidebar */
    .sidebar {
      background: var(--panel);
      border: 1px solid var(--divider);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      position: sticky;
      top: 100px;
      height: calc(100vh - 140px);
      padding: 16px;
      overflow: auto;
      display: flex;
      flex-direction: column;
    }

    .progress-overview {
      background: rgba(90,167,255,.1);
      border: 1px solid rgba(90,167,255,.2);
      border-radius: 12px;
      padding: 12px;
      margin-bottom: 16px;
    }
    
    .progress-bar {
      width: 100%;
      height: 8px;
      background: #0f141a;
      border-radius: 4px;
      overflow: hidden;
      margin: 8px 0;
    }
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--accent), var(--accent-2));
      width: 0%;
      transition: width .3s ease;
      border-radius: 4px;
    }

    .sidebar h2 { 
      font-size: 14px; 
      color: var(--muted); 
      margin: 8px 8px 12px; 
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .steps { 
      list-style: none; 
      margin: 0; 
      padding: 0; 
      display: grid; 
      gap: 4px; 
      flex: 1;
    }
    .steps li {
      display: grid; 
      grid-template-columns: 32px 1fr auto auto; 
      align-items: center; 
      gap: 8px;
      padding: 10px 12px; 
      border-radius: 12px; 
      cursor: pointer; 
      border: 1px solid transparent;
      transition: all .2s ease;
      position: relative;
    }
    .steps li:hover { 
      background: #11161c; 
      border-color: #212a35; 
      transform: translateX(2px);
    }
    .steps li.active { 
      background: linear-gradient(180deg, #1a2028, #14181e); 
      border: 1px solid #28313c;
      transform: translateX(4px);
    }
    .steps li.completed {
      background: linear-gradient(180deg, rgba(74,222,128,.15), rgba(74,222,128,.05));
      border-color: rgba(74,222,128,.3);
    }

    .step-idx {
      width: 28px; 
      height: 28px; 
      display: grid; 
      place-items: center; 
      border-radius: 8px;
      background: #0f141a; 
      border: 1px solid #2a3440; 
      color: var(--muted); 
      font-size: 12px; 
      font-weight: 700;
      transition: all .2s ease;
    }
    .steps li.completed .step-idx {
      background: var(--success);
      color: white;
      border-color: var(--success);
    }
    .steps li.active .step-idx {
      background: var(--accent);
      color: white;
      border-color: var(--accent);
    }

    .step-title { 
      font-weight: 600; 
      font-size: 13px;
    }
    .step-meta {
      font-size: 11px;
      color: var(--muted);
      text-align: right;
    }
    .step-difficulty {
      font-size: 16px;
      line-height: 1;
    }

    .shortcuts-help {
      margin-top: 12px;
      padding: 10px;
      background: rgba(255,255,255,.02);
      border: 1px dashed var(--divider);
      border-radius: 8px;
      font-size: 12px;
      color: var(--muted);
    }
    .kbd { 
      font: 600 11px/1 system-ui; 
      background: #0b0f12; 
      border: 1px solid #2a3440; 
      padding: 2px 5px; 
      border-radius: 4px; 
      color: var(--muted); 
    }

    /* Main */
    .main {
      background: var(--panel);
      border: 1px solid var(--divider);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      position: relative;
    }

    .toolbar {
      display: flex; 
      align-items: center; 
      gap: 12px; 
      padding: 16px 20px; 
      border-bottom: 1px solid var(--divider);
      background: linear-gradient(180deg, #12161c, #0f1318);
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .toolbar .spacer { flex: 1; }

    .section-pill {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      border: 1px solid #2b3441;
      padding: 6px 12px;
      border-radius: 999px;
      color: var(--text);
      background: rgba(90,167,255,.1);
      font-weight: 600;
    }

    .auto-save-indicator {
      font-size: 12px;
      color: var(--success);
      display: flex;
      align-items: center;
      gap: 4px;
      opacity: 0;
      transition: opacity .3s ease;
    }
    .auto-save-indicator.show { opacity: 1; }

    .section {
      display: none;
      padding: 24px 28px 32px;
      animation: slideIn .3s ease;
      position: relative;
    }
    .section.active { display: block; }

    @keyframes slideIn { 
      from { opacity: .7; transform: translateY(8px);} 
      to { opacity: 1; transform: translateY(0);} 
    }

    .section-header {
      margin-bottom: 20px;
    }
    .section h3 { 
      margin: 0 0 6px; 
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .section-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: grid;
      place-items: center;
      font-size: 16px;
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
    }
    .section p.hint { 
      margin: 0; 
      color: var(--muted); 
      line-height: 1.4;
    }

    .motivation-banner {
      background: linear-gradient(90deg, rgba(90,167,255,.1), rgba(255,117,195,.1));
      border: 1px solid rgba(90,167,255,.2);
      border-radius: 12px;
      padding: 12px 16px;
      margin-bottom: 20px;
      font-size: 14px;
      color: var(--text);
    }

    .grid { display: grid; gap: 16px; }
    .grid.cols-2 { grid-template-columns: repeat(2, minmax(0,1fr)); }
    .grid.cols-3 { grid-template-columns: repeat(3, minmax(0,1fr)); }

    .field-group {
      position: relative;
    }
    .field-group.required::before {
      content: "*";
      position: absolute;
      top: -2px;
      right: -8px;
      color: var(--danger);
      font-weight: bold;
      z-index: 1;
    }

    label { 
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600; 
      margin-bottom: 8px;
      font-size: 14px;
    }
    .field-help {
      width: 16px;
      height: 16px;
      border-radius: 50%;
      background: var(--muted);
      color: white;
      display: grid;
      place-items: center;
      font-size: 12px;
      font-weight: bold;
      cursor: help;
      position: relative;
    }
    .field-help:hover .tooltip {
      display: block;
    }
    .tooltip {
      display: none;
      position: absolute;
      bottom: 120%;
      left: 50%;
      transform: translateX(-50%);
      background: #1a1f26;
      border: 1px solid var(--divider);
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 12px;
      font-weight: normal;
      white-space: nowrap;
      z-index: 1000;
      box-shadow: var(--shadow);
    }

    .muted { color: var(--muted); font-weight: 500; }

    input[type="text"], input[type="number"], input[type="url"], input[type="email"], textarea, select {
      width: 100%;
      background: #0e1216;
      border: 1px solid #2b3441;
      border-radius: 12px;
      color: var(--text);
      padding: 12px 14px;
      outline: none;
      transition: all .2s ease;
      font-size: 14px;
    }
    textarea { min-height: 100px; resize: vertical; font-family: inherit; }

    input:focus, textarea:focus, select:focus { 
      border-color: var(--accent); 
      box-shadow: 0 0 0 3px rgba(90,167,255,.15);
      transform: translateY(-1px);
    }
    input:valid, textarea:valid, select:valid {
      border-color: rgba(74,222,128,.5);
    }

    .input-with-suggestions {
      position: relative;
    }
    .suggestions {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: #1a1f26;
      border: 1px solid var(--divider);
      border-top: none;
      border-radius: 0 0 12px 12px;
      max-height: 200px;
      overflow-y: auto;
      z-index: 100;
      display: none;
    }
    .suggestion {
      padding: 10px 14px;
      cursor: pointer;
      border-bottom: 1px solid var(--divider);
      transition: background .2s ease;
    }
    .suggestion:hover {
      background: rgba(90,167,255,.1);
    }
    .suggestion:last-child {
      border-bottom: none;
    }

    .row { display: grid; gap: 16px; grid-template-columns: 1fr 1fr; }

    .chips { 
      display: flex; 
      flex-wrap: wrap; 
      gap: 8px; 
      min-height: 40px;
      align-items: flex-start;
      align-content: flex-start;
    }
    .chip {
      border: 1px solid #2b3441; 
      background: #0f141a; 
      padding: 8px 12px; 
      border-radius: 20px;
      cursor: pointer; 
      user-select: none; 
      font-weight: 600; 
      color: var(--muted);
      transition: all .2s ease;
      position: relative;
      font-size: 13px;
    }
    .chip:hover {
      border-color: rgba(90,167,255,.4);
      transform: translateY(-1px);
    }
    .chip.active { 
      border-color: rgba(90,167,255,.6); 
      color: #fff; 
      background: linear-gradient(135deg, rgba(90,167,255,.25), rgba(255,117,195,.15));
      box-shadow: 0 4px 12px rgba(90,167,255,.2);
    }
    .chip.priority-high::after {
      content: "🔥";
      margin-left: 4px;
    }
    .chip.priority-medium::after {
      content: "⚡";
      margin-left: 4px;
    }

    .sortable-chips {
      min-height: 60px;
      border: 2px dashed transparent;
      border-radius: 12px;
      padding: 8px;
      transition: border-color .2s ease;
    }
    .sortable-chips.drag-over {
      border-color: var(--accent);
      background: rgba(90,167,255,.05);
    }

    .priority-section {
      margin-top: 16px;
      padding: 16px;
      background: rgba(255,117,195,.05);
      border: 1px solid rgba(255,117,195,.2);
      border-radius: 12px;
    }

    .notice { 
      border: 1px dashed #2c3440; 
      background: #0c1014; 
      padding: 12px 16px; 
      border-radius: 12px; 
      color: var(--muted);
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }
    .notice.warning {
      border-color: rgba(255,179,71,.3);
      background: rgba(255,179,71,.05);
      color: var(--warning);
    }
    .notice.success {
      border-color: rgba(74,222,128,.3);
      background: rgba(74,222,128,.05);
      color: var(--success);
    }

    .smart-defaults {
      margin-top: 12px;
    }
    .smart-defaults button {
      padding: 6px 10px;
      font-size: 12px;
      margin-right: 6px;
      margin-bottom: 6px;
    }

    .nav {
      display: flex; 
      gap: 12px; 
      justify-content: space-between;
      align-items: center;
      margin-top: 24px; 
      padding: 20px 28px; 
      border-top: 1px solid var(--divider);
      background: linear-gradient(180deg, transparent, rgba(0,0,0,.1));
    }

    .nav-left {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .skip-button {
      color: var(--muted);
      background: transparent;
      border: 1px dashed var(--divider);
      font-size: 13px;
    }

    .summary-box { 
      background: #0d1217; 
      border: 1px solid #2b3441; 
      border-radius: 12px; 
      padding: 16px; 
      white-space: pre-wrap;
      font-family: 'SF Mono', Monaco, monospace;
      font-size: 13px;
      line-height: 1.5;
    }

    .pill { 
      font-size: 12px; 
      border: 1px solid #2b3441; 
      padding: 4px 10px; 
      border-radius: 999px; 
      color: var(--muted); 
      background: rgba(255,255,255,.02);
    }

    /* Modal */
    .modal { 
      position: fixed; 
      inset: 0; 
      display: none; 
      place-items: center; 
      background: rgba(0,0,0,.65); 
      backdrop-filter: blur(4px); 
      z-index: 1000;
      animation: fadeIn .3s ease;
    }
    .modal.show { display: grid; }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .modal-card { 
      width: min(95vw, 1000px); 
      max-height: 90vh; 
      overflow: auto; 
      background: var(--panel); 
      border: 1px solid var(--divider); 
      border-radius: var(--radius); 
      box-shadow: 0 20px 60px rgba(0,0,0,.4);
      animation: slideUp .3s ease;
    }
    @keyframes slideUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .modal-head { 
      display: flex; 
      align-items: center; 
      justify-content: space-between; 
      padding: 16px 20px; 
      border-bottom: 1px solid var(--divider); 
      background: #0f1319; 
    }
    .modal-body { padding: 20px; }

    .collaboration-banner {
      background: linear-gradient(90deg, rgba(255,117,195,.1), rgba(90,167,255,.1));
      border: 1px solid rgba(255,117,195,.2);
      border-radius: 12px;
      padding: 12px 16px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    /* Mobile Responsiveness */
    @media (max-width: 900px) { 
      .app { 
        grid-template-columns: 1fr;
        gap: 16px;
        padding: 16px;
      } 
      .sidebar { 
        position: static; 
        height: auto;
        order: 2;
      } 
      .main {
        order: 1;
      }
      .grid.cols-2, .grid.cols-3 { 
        grid-template-columns: 1fr; 
      }
      .row {
        grid-template-columns: 1fr;
      }
      .header-actions {
        flex-wrap: wrap;
        gap: 6px;
      }
      .header-actions button {
        padding: 6px 8px;
        font-size: 13px;
      }
      .section {
        padding: 20px 16px 24px;
      }
      .nav {
        padding: 16px;
        flex-direction: column;
        gap: 12px;
      }
      .nav-left {
        order: 2;
      }
    }

    /* Touch improvements for mobile */
    @media (hover: none) and (pointer: coarse) {
      button, .chip, .steps li {
        min-height: 44px;
      }
      .chips {
        gap: 12px;
      }
    }

    /* Print styles */
    @media print {
      body * { visibility: hidden; }
      #summary, #summary * { visibility: visible; }
      #summary { 
        position: absolute; 
        left: 0; 
        top: 0; 
        right: 0; 
        color: #000; 
        background: #fff; 
        border: none; 
        padding: 0; 
      }
    }

    /* Advanced animations */
    .celebration {
      position: fixed;
      pointer-events: none;
      z-index: 10000;
    }

    @keyframes confetti {
      0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
      }
    }

    .confetti {
      position: absolute;
      width: 8px;
      height: 8px;
      background: var(--accent);
      animation: confetti 2s linear infinite;
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

  <div class="app">
    <header class="app-header">
      <div class="brand">
        <div class="logo" aria-hidden="true"></div>
        <div>
          <div>BREN7 CMS</div>
          <div class="muted" style="font-size:12px">Enhanced Website Onboarding</div>
        </div>
      </div>
      
      <div class="completion-banner" id="completionBanner">
        🎉 All sections complete! Ready to export.
      </div>

      <div class="header-actions">
        <div class="time-estimate">
          ⏱️ ~15 min to complete
        </div>
        <button id="btnCollaborate" class="button-ghost" title="Share with team">👥 Share</button>
        <button id="btnAll" class="button-ghost" title="Toggle All-in-One View">All Questions</button>
        <button id="btnSave" class="button">💾 Save</button>
        <button id="btnLoad" class="button">📂 Load</button>
        <button id="btnExport" class="button-primary button-pulse">📄 Export</button>
        <button id="btnSummary" class="button">📊 Summary</button>
        <button id="btnSOW" class="button-success">📋 Generate SOW</button>
      </div>
    </header>

    <aside class="sidebar">
      <div class="progress-overview">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <span style="font-weight: 600; font-size: 14px;">Overall Progress</span>
          <span id="overallProgress" style="font-weight: 700; color: var(--accent);">0%</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" id="progressFill"></div>
        </div>
        <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">
          <span id="completedFields">0</span> of <span id="totalFields">0</span> fields completed
        </div>
      </div>

      <h2>
        📋 Sections
        <span class="pill" id="sectionCounter">0/12</span>
      </h2>
      <ul class="steps" id="steps"></ul>
      
      <div class="shortcuts-help">
        <div style="font-weight: 600; margin-bottom: 6px;">⚡ Shortcuts</div>
        <div><span class="kbd">Tab</span> + <span class="kbd">Enter</span> Next section</div>
        <div><span class="kbd">Ctrl</span> + <span class="kbd">S</span> Quick save</div>
        <div><span class="kbd">Ctrl</span> + <span class="kbd">P</span> Print summary</div>
        <div><span class="kbd">Esc</span> Skip optional field</div>
      </div>
    </aside>

    <main class="main">
      <div class="toolbar">
        <div class="section-pill" id="sectionPill">
          <span>📋</span>
          <span>Section</span>
        </div>
        <div class="spacer"></div>
        <div class="auto-save-indicator" id="autoSaveIndicator">
          ✅ Auto-saved
        </div>
        <div class="muted" id="progress">0% complete</div>
      </div>

      <!-- Sections -->
      <div id="sections">
        <!-- 0: Organization -->
        <section class="section" data-key="org" data-title="Organization" data-icon="🏢" data-difficulty="📝">
          <div class="section-header">
            <h3>
              <div class="section-icon">🏢</div>
              <div>
                <div>Organization Details</div>
                <p class="hint">Basic information to personalize your BREN7 experience</p>
              </div>
            </h3>
          </div>
          
          <div class="motivation-banner">
            💡 <strong>Why this matters:</strong> We'll use these details to suggest relevant features and customize your site setup.
          </div>

          <div class="grid cols-2">
            <div class="field-group required">
              <label>
                Organization Name
                <div class="field-help">?
                  <div class="tooltip">Your official organization name as it should appear on the website</div>
                </div>
              </label>
              <div class="input-with-suggestions">
                <input type="text" data-field="org.name" placeholder="e.g., Calgary Animal Rescue" required />
              </div>
            </div>
            <div class="field-group">
              <label>
                Current Website
                <div class="field-help">?
                  <div class="tooltip">Your existing website URL (if any) - helps us understand your current setup</div>
                </div>
              </label>
              <input type="url" data-field="org.current_url" placeholder="https://your-current-site.com" />
            </div>
            <div class="field-group required">
              <label>
                Primary Contact
                <div class="field-help">?
                  <div class="tooltip">Main point person for this project</div>
                </div>
              </label>
              <input type="text" data-field="org.contact" placeholder="Full name" required />
            </div>
            <div class="field-group required">
              <label>
                Email Address
                <div class="field-help">?
                  <div class="tooltip">We'll use this for project updates and account setup</div>
                </div>
              </label>
              <input type="email" data-field="org.email" placeholder="name@example.org" required />
            </div>
            <div class="field-group">
              <label>Phone Number</label>
              <input type="text" data-field="org.phone" placeholder="(xxx) xxx-xxxx" />
            </div>
            <div class="field-group required">
              <label>
                Industry / Sector
                <div class="field-help">?
                  <div class="tooltip">Helps us suggest relevant features and templates</div>
                </div>
              </label>
              <div class="input-with-suggestions">
                <input type="text" data-field="org.industry" placeholder="Start typing..." required />
                <div class="suggestions" id="industrySuggestions">
                  <div class="suggestion">Non-profit Organization</div>
                  <div class="suggestion">Municipality / Government</div>
                  <div class="suggestion">Professional Association</div>
                  <div class="suggestion">Healthcare Organization</div>
                  <div class="suggestion">Educational Institution</div>
                  <div class="suggestion">Chamber of Commerce</div>
                  <div class="suggestion">Religious Organization</div>
                  <div class="suggestion">Arts & Culture</div>
                  <div class="suggestion">Sports & Recreation</div>
                  <div class="suggestion">Trade Union</div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- 1: Goals -->
        <section class="section" data-key="goals" data-title="Project Goals" data-icon="🎯" data-difficulty="💭">
          <div class="section-header">
            <h3>
              <div class="section-icon">🎯</div>
              <div>
                <div>Project Goals & Success Metrics</div>
                <p class="hint">Define what success looks like for your new website</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            🚀 <strong>Why this matters:</strong> Clear goals help us prioritize features and measure success after launch.
          </div>

          <label style="margin-bottom: 12px;">
            Primary Goals (drag to prioritize)
            <div class="field-help">?
              <div class="tooltip">Select your top 3-5 goals and drag them to rank by priority</div>
            </div>
          </label>
          <div class="sortable-chips chips" data-field="goals.list" data-type="sortable-chips" id="goalChips">
            <div class="chip" draggable="true">Modernize design & user experience</div>
            <div class="chip" draggable="true">Improve accessibility (WCAG 2.2 AA)</div>
            <div class="chip" draggable="true">Increase online donations</div>
            <div class="chip" draggable="true">Drive event registrations</div>
            <div class="chip" draggable="true">Grow membership base</div>
            <div class="chip" draggable="true">Recruit volunteers effectively</div>
            <div class="chip" draggable="true">Simplify content editing workflow</div>
            <div class="chip" draggable="true">Improve SEO & search visibility</div>
            <div class="chip" draggable="true">Enhance site performance & speed</div>
            <div class="chip" draggable="true">Better mobile experience</div>
            <div class="chip" draggable="true">Integrate with existing systems</div>
            <div class="chip" draggable="true">Reduce maintenance overhead</div>
          </div>

          <div class="priority-section">
            <label style="margin-bottom: 8px;">🔥 High Priority Goals</label>
            <div class="chips" id="highPriorityGoals"></div>
          </div>

          <div class="smart-defaults">
            <label style="margin-bottom: 8px;">💡 Quick Setup by Industry</label>
            <button type="button" onclick="applyIndustryDefaults('nonprofit')">Non-profit Defaults</button>
            <button type="button" onclick="applyIndustryDefaults('municipality')">Municipality Defaults</button>
            <button type="button" onclick="applyIndustryDefaults('association')">Association Defaults</button>
          </div>

          <div class="grid" style="margin-top: 20px;">
            <div class="field-group required">
              <label>
                Success Metrics
                <div class="field-help">?
                  <div class="tooltip">Specific, measurable goals (e.g., +30% donations, <2.5s load time)</div>
                </div>
              </label>
              <textarea data-field="goals.metrics" placeholder="e.g., Increase donations by 30% in 6 months, improve Core Web Vitals scores (LCP <2.5s, CLS <0.1), reduce bounce rate to under 40%" required></textarea>
            </div>
          </div>

          <div class="notice">
            <span>💡</span>
            <div>
              <strong>Pro tip:</strong> SMART goals (Specific, Measurable, Achievable, Relevant, Time-bound) help us design the right solution for you.
            </div>
          </div>
        </section>

        <!-- 2: Audience & Content -->
        <section class="section" data-key="content" data-title="Audience & Content" data-icon="👥" data-difficulty="💭">
          <div class="section-header">
            <h3>
              <div class="section-icon">👥</div>
              <div>
                <div>Target Audience & Content Strategy</div>
                <p class="hint">Understanding your users helps create better experiences</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            🎨 <strong>Why this matters:</strong> Knowing your audience shapes design decisions, content organization, and feature priorities.
          </div>

          <div class="grid cols-2">
            <div class="field-group required">
              <label>
                Primary Audiences
                <div class="field-help">?
                  <div class="tooltip">Who visits your site most? (e.g., donors, members, general public)</div>
                </div>
              </label>
              <textarea data-field="content.audiences" placeholder="e.g., Individual donors (40%), corporate sponsors (25%), volunteers (20%), media/press (10%), board members (5%)" required></textarea>
            </div>
            <div class="field-group required">
              <label>
                Top User Tasks & Journeys
                <div class="field-help">?
                  <div class="tooltip">What do visitors come to do on your site?</div>
                </div>
              </label>
              <textarea data-field="content.tasks" placeholder="e.g., Make a donation, register for events, find volunteer opportunities, read news updates, contact staff, download resources" required></textarea>
            </div>
            <div class="field-group">
              <label>
                Content Migration Plan
                <div class="field-help">?
                  <div class="tooltip">What content needs to move from your old site?</div>
                </div>
              </label>
              <textarea data-field="content.migration" placeholder="e.g., ~120 pages, 350 news articles (keep last 2 years), 50 event listings, photo galleries. Migration via CSV export + manual QA for key pages."></textarea>
            </div>
            <div class="field-group">
              <label>
                Languages Supported
                <div class="field-help">?
                  <div class="tooltip">Multilingual sites require special planning</div>
                </div>
              </label>
              <input type="text" data-field="content.languages" placeholder="e.g., English (primary), French" />
            </div>
          </div>

          <div class="smart-defaults">
            <label style="margin-bottom: 8px;">🚀 Content Strategy Templates</label>
            <button type="button" onclick="applyContentTemplate('donor-focused')">Donor-Focused Org</button>
            <button type="button" onclick="applyContentTemplate('member-focused')">Member Organization</button>
            <button type="button" onclick="applyContentTemplate('service-focused')">Service Provider</button>
          </div>
        </section>

        <!-- 3: Design Preferences -->
        <section class="section" data-key="design" data-title="Design & Branding" data-icon="🎨" data-difficulty="🔧">
          <div class="section-header">
            <h3>
              <div class="section-icon">🎨</div>
              <div>
                <div>Design & Brand Guidelines</div>
                <p class="hint">Visual direction and accessibility requirements</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            ✨ <strong>Why this matters:</strong> Strong branding builds trust and recognition. Accessibility ensures everyone can use your site.
          </div>

          <div class="grid cols-2">
            <div class="field-group">
              <label>
                Brand Colors & Style Guide
                <div class="field-help">?
                  <div class="tooltip">Include hex codes, font preferences, logo guidelines</div>
                </div>
              </label>
              <textarea data-field="design.brand" placeholder="e.g., Primary: #1e40af (blue), Secondary: #dc2626 (red), Accent: #059669 (green). Font: Open Sans. Logo: horizontal layout preferred. Avoid: bright pink, comic fonts."></textarea>
            </div>
            <div class="field-group">
              <label>
                Design Inspiration & Examples
                <div class="field-help">?
                  <div class="tooltip">Share 2-3 websites you admire and what you like about them</div>
                </div>
              </label>
              <textarea data-field="design.examples" placeholder="e.g., redcross.ca (clean donation flow), unitedway.org (clear impact stories), habitat.ca (volunteer signup process)"></textarea>
            </div>
            <div class="field-group">
              <label>
                Tone & Personality
                <div class="field-help">?
                  <div class="tooltip">How should your site feel to visitors?</div>
                </div>
              </label>
              <div class="chips" data-field="design.tone" data-type="chips">
                <div class="chip">Professional</div>
                <div class="chip">Friendly & Approachable</div>
                <div class="chip">Modern & Innovative</div>
                <div class="chip">Trustworthy & Reliable</div>
                <div class="chip">Inspiring & Uplifting</div>
                <div class="chip">Bold & Confident</div>
                <div class="chip">Warm & Community-focused</div>
                <div class="chip">Clean & Minimalist</div>
              </div>
            </div>
            <div class="field-group">
              <label>
                Accessibility Requirements
                <div class="field-help">?
                  <div class="tooltip">Legal requirements or organizational standards</div>
                </div>
              </label>
              <textarea data-field="design.accessibility" placeholder="e.g., WCAG 2.2 AA compliance required, high contrast mode, keyboard navigation, screen reader compatibility, alt text for all images, captions for videos."></textarea>
            </div>
          </div>

          <div class="notice">
            <span>🌟</span>
            <div>
              <strong>Accessibility Benefits Everyone:</strong> Features like clear navigation, good color contrast, and simple layouts improve usability for all visitors.
            </div>
          </div>
        </section>

        <!-- 4: Features (BREN7 widgets) -->
        <section class="section" data-key="features" data-title="Features & Modules" data-icon="⚙️" data-difficulty="🔧">
          <div class="section-header">
            <h3>
              <div class="section-icon">⚙️</div>
              <div>
                <div>Website Features & Functionality</div>
                <p class="hint">Select the BREN7 modules and custom features you need</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            🔧 <strong>Why this matters:</strong> Choosing the right features ensures your site supports your goals while staying manageable.
          </div>

          <label style="margin-bottom: 12px;">
            BREN7 Modules (select all that apply)
            <div class="field-help">?
              <div class="tooltip">These are pre-built BREN7 components. Each adds specific functionality.</div>
            </div>
          </label>
          <div class="chips" data-field="features.modules" data-type="chips">
            <div class="chip" data-complexity="simple" data-cost="included">📰 News / Blog</div>
            <div class="chip" data-complexity="medium" data-cost="included">📅 Events + Calendars</div>
            <div class="chip" data-complexity="complex" data-cost="premium">💳 Online Donations</div>
            <div class="chip" data-complexity="medium" data-cost="included">👥 Membership Management</div>
            <div class="chip" data-complexity="simple" data-cost="included">📋 Staff/Board Directories</div>
            <div class="chip" data-complexity="medium" data-cost="included">🏢 Programs / Services</div>
            <div class="chip" data-complexity="simple" data-cost="included">📍 Location Pages</div>
            <div class="chip" data-complexity="simple" data-cost="included">📚 Resource Library</div>
            <div class="chip" data-complexity="simple" data-cost="included">🖼️ Photo Galleries</div>
            <div class="chip" data-complexity="medium" data-cost="included">💼 Jobs / Careers</div>
            <div class="chip" data-complexity="medium" data-cost="included">📝 Custom Forms</div>
            <div class="chip" data-complexity="complex" data-cost="premium">🛒 E-commerce (basic)</div>
            <div class="chip" data-complexity="medium" data-cost="included">🎫 Event Registration</div>
            <div class="chip" data-complexity="simple" data-cost="included">📧 Newsletter Signup</div>
          </div>

          <div class="notice" style="margin-top: 16px;">
            <span>💡</span>
            <div>
              <strong>Feature Selection Tips:</strong>
              <br>• Start simple - you can always add modules later
              <br>• Consider who will maintain each feature
              <br>• Complex features (marked 🔧) may require additional setup time
            </div>
          </div>

          <div class="grid" style="margin-top: 20px;">
            <div class="field-group">
              <label>
                Custom Functionality Requirements
                <div class="field-help">?
                  <div class="tooltip">Describe any unique workflows or integrations beyond standard modules</div>
                </div>
              </label>
              <textarea data-field="features.custom" placeholder="e.g., Custom volunteer application with approval workflow, integration with existing member database, specialized reporting dashboard, multi-step donation process with recurring options."></textarea>
            </div>
          </div>

          <div class="smart-defaults">
            <label style="margin-bottom: 8px;">🎯 Feature Packages by Organization Type</label>
            <button type="button" onclick="applyFeaturePackage('charity')">Charity Essentials</button>
            <button type="button" onclick="applyFeaturePackage('association')">Association Pro</button>
            <button type="button" onclick="applyFeaturePackage('municipality')">Municipal Standard</button>
          </div>
        </section>

        <!-- 5: Integrations -->
        <section class="section" data-key="integrations" data-title="System Integrations" data-icon="🔗" data-difficulty="🔧">
          <div class="section-header">
            <h3>
              <div class="section-icon">🔗</div>
              <div>
                <div>Third-Party Integrations</div>
                <p class="hint">Connect your website to existing tools and services</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            🔗 <strong>Why this matters:</strong> Seamless integrations reduce data entry, improve workflows, and provide better user experiences.
          </div>

          <div class="grid cols-2">
            <div class="field-group">
              <label>
                CRM / Donor Management
                <div class="field-help">?
                  <div class="tooltip">Your customer/donor database system</div>
                </div>
              </label>
              <div class="input-with-suggestions">
                <input data-field="integrations.crm" type="text" placeholder="Start typing..." />
                <div class="suggestions" id="crmSuggestions">
                  <div class="suggestion">Salesforce Nonprofit Cloud</div>
                  <div class="suggestion">Raiser's Edge NXT</div>
                  <div class="suggestion">Neon CRM</div>
                  <div class="suggestion">Wild Apricot</div>
                  <div class="suggestion">HubSpot (Free/Nonprofit)</div>
                  <div class="suggestion">DonorPerfect</div>
                  <div class="suggestion">Bloomerang</div>
                  <div class="suggestion">Little Green Light</div>
                  <div class="suggestion">eTapestry</div>
                  <div class="suggestion">CiviCRM</div>
                </div>
              </div>
            </div>
            <div class="field-group">
              <label>
                Email Marketing Platform
                <div class="field-help">?
                  <div class="tooltip">For newsletters and donor communications</div>
                </div>
              </label>
              <div class="input-with-suggestions">
                <input data-field="integrations.email" type="text" placeholder="Start typing..." />
                <div class="suggestions" id="emailSuggestions">
                  <div class="suggestion">Mailchimp</div>
                  <div class="suggestion">Constant Contact</div>
                  <div class="suggestion">Campaign Monitor</div>
                  <div class="suggestion">SendGrid</div>
                  <div class="suggestion">AWeber</div>
                  <div class="suggestion">ConvertKit</div>
                  <div class="suggestion">Emma</div>
                  <div class="suggestion">ActiveCampaign</div>
                </div>
              </div>
            </div>
            <div class="field-group">
              <label>
                Payment Processing
                <div class="field-help">?
                  <div class="tooltip">For donations, memberships, and event fees</div>
                </div>
              </label>
              <div class="input-with-suggestions">
                <input data-field="integrations.payments" type="text" placeholder="Start typing..." />
                <div class="suggestions" id="paymentSuggestions">
                  <div class="suggestion">Stripe</div>
                  <div class="suggestion">PayPal</div>
                  <div class="suggestion">Authorize.Net</div>
                  <div class="suggestion">Square</div>
                  <div class="suggestion">Moneris (Canada)</div>
                  <div class="suggestion">Canada Helps</div>
                  <div class="suggestion">Network for Good</div>
                  <div class="suggestion">PaymentSpring</div>
                </div>
              </div>
            </div>
            <div class="field-group">
              <label>
                Analytics & Tracking
                <div class="field-help">?
                  <div class="tooltip">Website analytics and conversion tracking</div>
                </div>
              </label>
              <div class="input-with-suggestions">
                <input data-field="integrations.analytics" type="text" placeholder="Start typing..." />
                <div class="suggestions" id="analyticsSuggestions">
                  <div class="suggestion">Google Analytics 4</div>
                  <div class="suggestion">Google Tag Manager</div>
                  <div class="suggestion">Meta Pixel (Facebook)</div>
                  <div class="suggestion">Hotjar</div>
                  <div class="suggestion">Microsoft Clarity</div>
                  <div class="suggestion">Adobe Analytics</div>
                </div>
              </div>
            </div>
          </div>

          <div class="notice warning">
            <span>⚠️</span>
            <div>
              <strong>Integration Complexity:</strong> Each integration adds setup time and potential maintenance. We'll help prioritize based on your immediate needs vs. nice-to-haves.
            </div>
          </div>
        </section>

        <!-- 6: SEO & Content Strategy -->
        <section class="section" data-key="seo" data-title="SEO & Performance" data-icon="📈" data-difficulty="🔧">
          <div class="section-header">
            <h3>
              <div class="section-icon">📈</div>
              <div>
                <div>SEO & Website Performance</div>
                <p class="hint">Help people find your site and ensure it loads quickly</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            🚀 <strong>Why this matters:</strong> Good SEO brings more visitors, and fast loading keeps them engaged. Both directly impact your goals.
          </div>

          <div class="grid cols-2">
            <div class="field-group">
              <label>
                Priority Keywords & Topics
                <div class="field-help">?
                  <div class="tooltip">What terms should people find you for?</div>
                </div>
              </label>
              <textarea data-field="seo.keywords" placeholder="e.g., animal rescue Calgary, pet adoption Alberta, volunteer opportunities Calgary, donate to animals, spay neuter clinic"></textarea>
            </div>
            <div class="field-group">
              <label>
                URL Structure Strategy
                <div class="field-help">?
                  <div class="tooltip">Clean, descriptive URLs help both users and search engines</div>
                </div>
              </label>
              <textarea data-field="seo.urls" placeholder="e.g., /adopt/dogs, /volunteer/opportunities, /about/our-mission. Need 301 redirects from old site URLs: /oldpage.html → /about/our-mission"></textarea>
            </div>
            <div class="field-group">
              <label>
                Content Management & Governance
                <div class="field-help">?
                  <div class="tooltip">Who creates content and how often?</div>
                </div>
              </label>
              <textarea data-field="seo.governance" placeholder="e.g., Marketing Manager creates content, Executive Director approves. News posts 2x/week, program updates monthly, annual report yearly. Style guide for tone and formatting."></textarea>
            </div>
            <div class="field-group">
              <label>
                Performance Targets
                <div class="field-help">?
                  <div class="tooltip">Core Web Vitals and loading speed goals</div>
                </div>
              </label>
              <input data-field="seo.performance" type="text" placeholder="e.g., LCP < 2.5s, CLS < 0.1, FID < 100ms, 90+ PageSpeed score" />
            </div>
          </div>

          <div class="smart-defaults">
            <label style="margin-bottom: 8px;">🎯 SEO Strategy Templates</label>
            <button type="button" onclick="applySEOTemplate('local-org')">Local Organization</button>
            <button type="button" onclick="applySEOTemplate('service-provider')">Service Provider</button>
            <button type="button" onclick="applySEOTemplate('advocacy')">Advocacy Group</button>
          </div>

          <div class="notice success">
            <span>⚡</span>
            <div>
              <strong>Performance Promise:</strong> All BREN7 sites are optimized for speed with modern hosting, image optimization, and clean code.
            </div>
          </div>
        </section>

        <!-- 7: Users & Roles -->
        <section class="section" data-key="users" data-title="Users & Permissions" data-icon="👤" data-difficulty="💭">
          <div class="section-header">
            <h3>
              <div class="section-icon">👤</div>
              <div>
                <div>User Management & Workflows</div>
                <p class="hint">Who will manage your site and how content gets published</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            🔐 <strong>Why this matters:</strong> Clear roles and workflows prevent content chaos and ensure your site stays current and secure.
          </div>

          <div class="grid cols-2">
            <div class="field-group required">
              <label>
                Number of Website Editors
                <div class="field-help">?
                  <div class="tooltip">People who will regularly add/edit content</div>
                </div>
              </label>
              <input data-field="users.count" type="number" min="1" max="50" placeholder="e.g., 5" required />
            </div>
            <div class="field-group">
              <label>
                Role Structure & Permissions
                <div class="field-help">?
                  <div class="tooltip">Who can do what on the website?</div>
                </div>
              </label>
              <textarea data-field="users.roles" placeholder="e.g., Super Admin (ED) - full access, Content Editors (3 staff) - edit assigned sections, Volunteer Coordinator - events only, Board Members - view drafts only"></textarea>
            </div>
            <div class="field-group">
              <label>
                Training Requirements
                <div class="field-help">?
                  <div class="tooltip">How do you prefer to learn new systems?</div>
                </div>
              </label>
              <div class="chips" data-field="users.training" data-type="chips">
                <div class="chip">Live training sessions</div>
                <div class="chip">Recorded video tutorials</div>
                <div class="chip">Written documentation</div>
                <div class="chip">One-on-one coaching</div>
                <div class="chip">Quick reference cards</div>
                <div class="chip">Practice site for testing</div>
              </div>
            </div>
            <div class="field-group">
              <label>
                Content Approval Workflow
                <div class="field-help">?
                  <div class="tooltip">Does content need approval before publishing?</div>
                </div>
              </label>
              <textarea data-field="users.workflow" placeholder="e.g., Draft → Content Editor review → Executive Director approval → Publish. Exception: News posts can be published directly by Marketing Manager."></textarea>
            </div>
          </div>

          <div class="notice">
            <span>📚</span>
            <div>
              <strong>Training Included:</strong> All BREN7 projects include comprehensive training tailored to your team's preferences and technical comfort level.
            </div>
          </div>
        </section>

        <!-- 8: Hosting & Domain -->
        <section class="section" data-key="hosting" data-title="Hosting & Security" data-icon="🔒" data-difficulty="🔧">
          <div class="section-header">
            <h3>
              <div class="section-icon">🔒</div>
              <div>
                <div>Hosting, Domain & Security</div>
                <p class="hint">Technical infrastructure and security requirements</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            🔒 <strong>Why this matters:</strong> Reliable hosting and strong security protect your organization's reputation and ensure your site is always accessible.
          </div>

          <div class="grid cols-2">
            <div class="field-group">
              <label>
                Domain Names & Subdomains
                <div class="field-help">?
                  <div class="tooltip">What web addresses will your site use?</div>
                </div>
              </label>
              <textarea data-field="hosting.domains" placeholder="e.g., example.org (main site), donate.example.org (giving portal), members.example.org (member login)"></textarea>
            </div>
            <div class="field-group">
              <label>
                Security & Compliance Requirements
                <div class="field-help">?
                  <div class="tooltip">Any specific security standards your organization must meet?</div>
                </div>
              </label>
              <textarea data-field="hosting.security" placeholder="e.g., SSL certificate required, HSTS headers, CSP policy, two-factor authentication for admins, PCI compliance for donations, PIPEDA compliance (Canada)"></textarea>
            </div>
            <div class="field-group">
              <label>
                Development Environments
                <div class="field-help">?
                  <div class="tooltip">Do you need a staging site for testing changes?</div>
                </div>
              </label>
              <div class="chips" data-field="hosting.env" data-type="chips">
                <div class="chip">Production only</div>
                <div class="chip">Staging + Production</div>
                <div class="chip">Dev + Staging + Production</div>
                <div class="chip">Password-protected preview</div>
              </div>
            </div>
            <div class="field-group">
              <label>
                Uptime & Performance SLA
                <div class="field-help">?
                  <div class="tooltip">What level of reliability do you need?</div>
                </div>
              </label>
              <input data-field="hosting.sla" type="text" placeholder="e.g., 99.9% uptime, < 3 second response time" />
            </div>
          </div>

                      <div class="notice success">
            <span>🛡️</span>
            <div>
              <strong>Enterprise Security:</strong> BREN7 includes SSL certificates, automated backups, security monitoring, and WCAG compliance as standard.
            </div>
          </div>
        </section>

        <!-- 9: Timeline & Budget -->
        <section class="section" data-key="planning" data-title="Timeline & Budget" data-icon="📅" data-difficulty="💭">
          <div class="section-header">
            <h3>
              <div class="section-icon">📅</div>
              <div>
                <div>Project Timeline & Investment</div>
                <p class="hint">Planning constraints and budget expectations</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            ⏰ <strong>Why this matters:</strong> Realistic timelines and clear budgets help ensure project success and prevent scope creep.
          </div>

          <div class="grid cols-2">
            <div class="field-group required">
              <label>
                Target Launch Date
                <div class="field-help">?
                  <div class="tooltip">When do you need the site to go live?</div>
                </div>
              </label>
              <input data-field="planning.launch" type="date" required />
              <div class="muted" style="font-size: 12px; margin-top: 4px;">Typical projects take 8-16 weeks from contract to launch</div>
            </div>
            <div class="field-group required">
              <label>
                Budget Range (CAD)
                <div class="field-help">?
                  <div class="tooltip">Total project investment including design, development, and initial setup</div>
                </div>
              </label>
              <select data-field="planning.budget" required>
                <option value="">Select budget range...</option>
                <option value="under-15k">Under $15,000</option>
                <option value="15k-25k">$15,000 - $25,000</option>
                <option value="25k-40k">$25,000 - $40,000</option>
                <option value="40k-60k">$40,000 - $60,000</option>
                <option value="60k-100k">$60,000 - $100,000</option>
                <option value="over-100k">Over $100,000</option>
              </select>
            </div>
            <div class="field-group">
              <label>
                Project Constraints & Risks
                <div class="field-help">?
                  <div class="tooltip">What could delay or complicate the project?</div>
                </div>
              </label>
              <textarea data-field="planning.risks" placeholder="e.g., Board approval required by December, content migration depends on old vendor, integration with legacy system has unknown complexity, staff availability limited during busy season"></textarea>
            </div>
            <div class="field-group">
              <label>
                Key Stakeholders & Decision Makers
                <div class="field-help">?
                  <div class="tooltip">Who needs to approve major decisions?</div>
                </div>
              </label>
              <textarea data-field="planning.stakeholders" placeholder="e.g., Executive Director (final approval), Board Chair (budget approval), Marketing Committee (design approval), IT Manager (technical review)"></textarea>
            </div>
          </div>

          <div class="notice warning" id="budgetWarning" style="display: none;">
            <span>⚠️</span>
            <div>
              <strong>Budget vs. Features:</strong> <span id="budgetAdvice"></span>
            </div>
          </div>

          <div class="collaboration-banner">
            <span>👥</span>
            <div>
              <strong>Collaborative Planning:</strong> We'll work with you to balance timeline, budget, and features for the best possible outcome.
            </div>
          </div>
        </section>

        <!-- 10: Analytics & Success -->
        <section class="section" data-key="analytics" data-title="Analytics & Reporting" data-icon="📊" data-difficulty="🔧">
          <div class="section-header">
            <h3>
              <div class="section-icon">📊</div>
              <div>
                <div>Analytics & Success Measurement</div>
                <p class="hint">How you'll track progress toward your goals</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            📈 <strong>Why this matters:</strong> You can't improve what you don't measure. Good analytics help you optimize your site's performance over time.
          </div>

          <div class="grid cols-2">
            <div class="field-group">
              <label>
                Key Performance Indicators (KPIs)
                <div class="field-help">?
                  <div class="tooltip">Specific metrics that tie to your goals</div>
                </div>
              </label>
              <textarea data-field="analytics.kpis" placeholder="e.g., Monthly donations ($), New member signups (#), Event registrations (#), Newsletter subscriptions (#), Volunteer applications (#), Page load speed (seconds), Bounce rate (%)"></textarea>
            </div>
            <div class="field-group">
              <label>
                Reporting Schedule
                <div class="field-help">?
                  <div class="tooltip">How often do you want to review website performance?</div>
                </div>
              </label>
              <div class="chips" data-field="analytics.cadence" data-type="chips">
                <div class="chip">Weekly dashboards</div>
                <div class="chip">Monthly reports</div>
                <div class="chip">Quarterly reviews</div>
                <div class="chip">Annual analysis</div>
                <div class="chip">Campaign-specific tracking</div>
              </div>
            </div>
            <div class="field-group">
              <label>
                Dashboard & Reporting Needs
                <div class="field-help">?
                  <div class="tooltip">What analytics tools and reports would be most helpful?</div>
                </div>
              </label>
              <textarea data-field="analytics.dashboards" placeholder="e.g., Google Analytics dashboard for web traffic, donation tracking report, email campaign performance, social media referrals, search ranking reports"></textarea>
            </div>
            <div class="field-group">
              <label>
                A/B Testing Opportunities
                <div class="field-help">?
                  <div class="tooltip">What would you like to test and optimize?</div>
                </div>
              </label>
              <textarea data-field="analytics.ab" placeholder="e.g., Donation button placement, homepage hero messages, volunteer signup flow, event registration forms"></textarea>
            </div>
          </div>

          <div class="notice">
            <span>📋</span>
            <div>
              <strong>Analytics Setup Included:</strong> We'll configure Google Analytics 4, set up goal tracking, and create custom dashboards for your key metrics.
            </div>
          </div>
        </section>

        <!-- 11: Notes -->
        <section class="section" data-key="notes" data-title="Additional Notes" data-icon="📝" data-difficulty="📝">
          <div class="section-header">
            <h3>
              <div class="section-icon">📝</div>
              <div>
                <div>Additional Information</div>
                <p class="hint">Anything else we should know about your project?</p>
              </div>
            </h3>
          </div>

          <div class="motivation-banner">
            💭 <strong>Your space:</strong> Share any additional context, concerns, or ideas that didn't fit in the previous sections.
          </div>

          <div class="field-group">
            <label>
              Additional Notes & Requirements
              <div class="field-help">?
                <div class="tooltip">Special requirements, past experiences, specific concerns, or anything else relevant</div>
              </div>
            </label>
            <textarea data-field="notes.freeform" placeholder="e.g., Previous website was difficult to update, concerned about mobile performance, need multilingual capability in future, have specific accessibility requirements due to user base, integration with existing database is critical..." style="min-height:180px"></textarea>
          </div>

          <div class="notice">
            <span>🤝</span>
            <div>
              <strong>We're Here to Help:</strong> No detail is too small. The more context you provide, the better we can tailor the solution to your needs.
            </div>
          </div>
        </section>
      </div>

      <div class="nav">
        <div class="nav-left">
          <button id="prev" disabled>← Previous</button>
          <button id="skip" class="skip-button" style="display: none;">Skip Section</button>
        </div>
        <div>
          <button id="next" class="button-primary">Next →</button>
        </div>
      </div>
    </main>
  </div>

  <!-- Summary Modal -->
  <div class="modal" id="modal">
    <div class="modal-card">
      <div class="modal-head">
        <div><strong id="modalTitle">Summary</strong></div>
        <div class="inline">
          <button id="copySummary" class="button">📋 Copy</button>
          <button id="printSummary" class="button">🖨️ Print</button>
          <button id="emailSummary" class="button">📧 Email</button>
          <button id="closeModal" class="button">✕ Close</button>
        </div>
      </div>
      <div class="modal-body">
        <div class="summary-box" id="summary"></div>
      </div>
    </div>
  </div>

  <!-- Collaboration Modal -->
  <div class="modal" id="collaborationModal">
    <div class="modal-card">
      <div class="modal-head">
        <div><strong>Share for Collaboration</strong></div>
        <div>
          <button id="closeCollabModal" class="button">✕ Close</button>
        </div>
      </div>
      <div class="modal-body">
        <p>Share this form with team members to gather input:</p>
        <div style="margin: 16px 0;">
          <label>Shareable Link</label>
          <div style="display: flex; gap: 8px; margin-top: 4px;">
            <input type="text" id="shareLink" readonly style="flex: 1;" />
            <button id="copyLink" class="button">Copy Link</button>
          </div>
        </div>
        <div class="notice">
          <span>💡</span>
          <div>Team members can view and comment on responses. Only the original creator can make changes.</div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
  <script>
    (function(){
      const $sections = $("#sections .section");
      const $steps = $("#steps");
      const $progress = $("#progress");
      const $sectionPill = $("#sectionPill");
      const $progressFill = $("#progressFill");
      const $overallProgress = $("#overallProgress");
      const $completedFields = $("#completedFields");
      const $totalFields = $("#totalFields");
      const $sectionCounter = $("#sectionCounter");
      const $autoSaveIndicator = $("#autoSaveIndicator");
      const $completionBanner = $("#completionBanner");
      
      const STORAGE_KEY = "bren7_onboarding_enhanced_v2";
      
      // Section metadata
      const sectionData = [
        { emoji: "🏢", difficulty: "📝", fields: 6, description: "Basic org info" },
        { emoji: "🎯", difficulty: "💭", fields: 4, description: "Project goals" },
        { emoji: "👥", difficulty: "💭", fields: 4, description: "Audience analysis" },
        { emoji: "🎨", difficulty: "🔧", fields: 4, description: "Design direction" },
        { emoji: "⚙️", difficulty: "🔧", fields: 6, description: "Feature selection" },
        { emoji: "🔗", difficulty: "🔧", fields: 4, description: "System connections" },
        { emoji: "📈", difficulty: "🔧", fields: 4, description: "SEO strategy" },
        { emoji: "👤", difficulty: "💭", fields: 4, description: "User management" },
        { emoji: "🔒", difficulty: "🔧", fields: 4, description: "Technical setup" },
        { emoji: "📅", difficulty: "💭", fields: 4, description: "Project planning" },
        { emoji: "📊", difficulty: "🔧", fields: 4, description: "Analytics setup" },
        { emoji: "📝", difficulty: "📝", fields: 1, description: "Final thoughts" }
      ];

      // Build sidebar steps with enhanced metadata
      $sections.each(function(i){
        const title = $(this).data('title');
        const data = sectionData[i];
        const li = $(`
          <li data-idx="${i}">
            <div class="step-idx">${i+1}</div>
            <div class="step-title">${title}</div>
            <div class="step-meta">
              <div>${data.fields} fields</div>
              <div class="step-difficulty">${data.difficulty}</div>
            </div>
            <div class="step-difficulty">${data.emoji}</div>
          </li>
        `);
        $steps.append(li);
      });

      let currentSection = 0;
      let autoSaveTimeout;

      function setActive(idx){
        idx = Math.max(0, Math.min(idx, $sections.length-1));
        currentSection = idx;
        
        $sections.removeClass('active').eq(idx).addClass('active');
        $steps.find('li').removeClass('active').eq(idx).addClass('active');
        
        const title = $sections.eq(idx).data('title');
        const data = sectionData[idx];
        $sectionPill.html(`
          <span>${data.emoji}</span>
          <span>${(idx+1).toString().padStart(2,'0')} • ${title}</span>
        `);
        
        updateProgress();
        updateNavigation();
        localStorage.setItem('bren7_onboarding_idx', idx);
        
        // Celebration for section completion
        if (isSectionComplete(idx)) {
          showSectionCompletedFeedback(idx);
        }
      }

      function updateNavigation() {
        $("#prev").prop('disabled', currentSection === 0);
        $("#next").text(currentSection === $sections.length - 1 ? 'Complete 🎉' : 'Next →');
        
        // Show skip button for optional sections
        const isRequired = $sections.eq(currentSection).find('.field-group.required').length > 0;
        $("#skip").toggle(!isRequired && currentSection < $sections.length - 1);
      }

      function isSectionComplete(idx) {
        const section = $sections.eq(idx);
        const requiredFields = section.find('.field-group.required [data-field]');
        
        if (requiredFields.length === 0) return true; // No required fields
        
        return requiredFields.toArray().every(field => {
          const $field = $(field);
          const type = $field.data('type') || field.tagName.toLowerCase();
          
          if (type === 'chips' || type === 'sortable-chips') {
            return $field.find('.chip.active').length > 0;
          } else if (field.type === 'checkbox') {
            return field.checked;
          } else {
            return String($field.val()).trim().length > 0;
          }
        });
      }

      function showSectionCompletedFeedback(idx) {
        $steps.find('li').eq(idx).addClass('completed');
        
        // Create confetti effect
        if (idx > 0) { // Don't show for first section
          createConfetti();
        }
      }

      function createConfetti() {
        const colors = ['#5aa7ff', '#ff75c3', '#6dd3ff', '#4ade80'];
        const container = $('<div class="celebration"></div>').appendTo('body');
        
        for (let i = 0; i < 30; i++) {
          const confetti = $('<div class="confetti"></div>');
          confetti.css({
            left: Math.random() * 100 + '%',
            background: colors[Math.floor(Math.random() * colors.length)],
            animationDelay: Math.random() * 2 + 's',
            animationDuration: (Math.random() * 2 + 1) + 's'
          });
          container.append(confetti);
        }
        
        setTimeout(() => container.remove(), 3000);
      }

      // Chips toggle with priority indicators
      $(document).on('click', '.chip', function(){
        $(this).toggleClass('active');
        
        // Special handling for goal prioritization
        if ($(this).closest('#goalChips').length) {
          updateGoalPriorities();
        }
        
        autosave();
      });

      function updateGoalPriorities() {
        const activeGoals = $('#goalChips .chip.active');
        const highPriorityContainer = $('#highPriorityGoals');
        
        highPriorityContainer.empty();
        
        activeGoals.slice(0, 3).each(function(i) {
          const clone = $(this).clone();
          clone.addClass(i === 0 ? 'priority-high' : i === 1 ? 'priority-medium' : '');
          highPriorityContainer.append(clone);
        });
      }

      // Drag and drop for sortable chips
      if (typeof Sortable !== 'undefined') {
        const sortableElements = document.querySelectorAll('.sortable-chips');
        sortableElements.forEach(el => {
          new Sortable(el, {
            animation: 150,
            ghostClass: 'ghost',
            onEnd: function() {
              autosave();
              if (el.id === 'goalChips') {
                updateGoalPriorities();
              }
            }
          });
        });
      }

      // Enhanced auto-suggestions
      const suggestions = {
        industry: ['Non-profit Organization', 'Municipality / Government', 'Professional Association', 'Healthcare Organization', 'Educational Institution', 'Chamber of Commerce', 'Religious Organization', 'Arts & Culture', 'Sports & Recreation', 'Trade Union'],
        crm: ['Salesforce Nonprofit Cloud', 'Raiser\'s Edge NXT', 'Neon CRM', 'Wild Apricot', 'HubSpot (Free/Nonprofit)', 'DonorPerfect', 'Bloomerang', 'Little Green Light', 'eTapestry', 'CiviCRM'],
        email: ['Mailchimp', 'Constant Contact', 'Campaign Monitor', 'SendGrid', 'AWeber', 'ConvertKit', 'Emma', 'ActiveCampaign'],
        payments: ['Stripe', 'PayPal', 'Authorize.Net', 'Square', 'Moneris (Canada)', 'Canada Helps', 'Network for Good', 'PaymentSpring'],
        analytics: ['Google Analytics 4', 'Google Tag Manager', 'Meta Pixel (Facebook)', 'Hotjar', 'Microsoft Clarity', 'Adobe Analytics']
      };

      // Setup suggestion handlers
      function setupSuggestions() {
        Object.keys(suggestions).forEach(key => {
          const input = $(`input[data-field*="${key}"]`);
          if (input.length) {
            const suggestionsContainer = input.siblings('.suggestions');
            if (suggestionsContainer.length) {
              input.on('focus', () => suggestionsContainer.show());
              input.on('blur', () => setTimeout(() => suggestionsContainer.hide(), 150));
              
              suggestionsContainer.on('click', '.suggestion', function() {
                input.val($(this).text());
                suggestionsContainer.hide();
                autosave();
              });
            }
          }
        });
      }

      // Smart defaults functions
      window.applyIndustryDefaults = function(type) {
        const defaults = {
          nonprofit: ['Increase online donations', 'Recruit volunteers effectively', 'Improve accessibility (WCAG 2.2 AA)', 'Modernize design & user experience'],
          municipality: ['Improve accessibility (WCAG 2.2 AA)', 'Better mobile experience', 'Simplify content editing workflow', 'Enhance site performance & speed'],
          association: ['Grow membership base', 'Drive event registrations', 'Improve SEO & search visibility', 'Integrate with existing systems']
        };
        
        if (defaults[type]) {
          $('#goalChips .chip').removeClass('active');
          defaults[type].forEach(goal => {
            $('#goalChips .chip').filter((i, el) => $(el).text().includes(goal.split(' ')[0])).addClass('active');
          });
          updateGoalPriorities();
          autosave();
        }
      };

      window.applyContentTemplate = function(type) {
        const templates = {
          'donor-focused': {
            audiences: 'Individual donors (50%), corporate sponsors (25%), volunteers (15%), media/press (10%)',
            tasks: 'Make donations, read impact stories, sign up for newsletters, volunteer, share on social media'
          },
          'member-focused': {
            audiences: 'Current members (60%), prospective members (25%), board/leadership (10%), partners (5%)',
            tasks: 'Access member resources, renew membership, register for events, networking, professional development'
          },
          'service-focused': {
            audiences: 'Service users/clients (40%), referring organizations (30%), funders (20%), community (10%)',
            tasks: 'Find services, access resources, make referrals, apply for programs, get support information'
          }
        };
        
        if (templates[type]) {
          $('textarea[data-field="content.audiences"]').val(templates[type].audiences);
          $('textarea[data-field="content.tasks"]').val(templates[type].tasks);
          autosave();
        }
      };

      window.applyFeaturePackage = function(type) {
        const packages = {
          charity: ['📰 News / Blog', '💳 Online Donations', '📅 Events + Calendars', '📝 Custom Forms', '📧 Newsletter Signup'],
          association: ['👥 Membership Management', '📅 Events + Calendars', '📋 Staff/Board Directories', '💼 Jobs / Careers', '📚 Resource Library'],
          municipality: ['📰 News / Blog', '📋 Staff/Board Directories', '📝 Custom Forms', '📍 Location Pages', '📅 Events + Calendars']
        };
        
        if (packages[type]) {
          $('[data-field="features.modules"] .chip').removeClass('active');
          packages[type].forEach(feature => {
            $('[data-field="features.modules"] .chip').filter((i, el) => $(el).text().trim() === feature).addClass('active');
          });
          autosave();
        }
      };

      window.applySEOTemplate = function(type) {
        const templates = {
          'local-org': {
            keywords: 'organization name + city, services + location, cause + local area',
            urls: '/about, /services, /contact, /donate, /volunteer, /news'
          },
          'service-provider': {
            keywords: 'services offered, professional terms, certification keywords',
            urls: '/services/service-name, /about/team, /resources, /contact'
          },
          'advocacy': {
            keywords: 'cause keywords, policy terms, advocacy topics',
            urls: '/issues, /take-action, /resources, /news, /about'
          }
        };
        
        if (templates[type]) {
          $('textarea[data-field="seo.keywords"]').val(templates[type].keywords);
          $('textarea[data-field="seo.urls"]').val(templates[type].urls);
          autosave();
        }
      };

      // Navigation
      $("#next").on('click', function() {
        if (currentSection === $sections.length - 1) {
          // Complete - show celebration
          showCompletionCelebration();
        } else {
          setActive(currentSection + 1);
        }
      });
      
      $("#prev").on('click', () => setActive(currentSection - 1));
      $("#skip").on('click', () => setActive(currentSection + 1));
      
      $steps.on('click', 'li', function() {
        setActive($(this).data('idx'));
      });

      function showCompletionCelebration() {
        $completionBanner.show();
        createConfetti();
        $("#btnExport").addClass('button-pulse');
        
        // Auto-scroll to export button
        $('html, body').animate({
          scrollTop: $('.header-actions').offset().top - 100
        }, 500);
      }

      // All-in-one toggle
      let allMode = false;
      $("#btnAll").on('click', function(){
        allMode = !allMode;
        $(this).text(allMode ? '📋 Sections' : '📄 All Questions');
        
        if (allMode) {
          $sections.addClass('active');
          $steps.find('li').removeClass('active');
          $sectionPill.html('<span>📄</span><span>All Questions</span>');
          $('.nav').hide();
        } else {
          $sections.removeClass('active');
          setActive(currentSection);
          $('.nav').show();
        }
      });

      // Enhanced data management
      function getData(){
        const data = {};
        $sections.each(function(){
          const key = $(this).data('key');
          data[key] = data[key] || {};
          
          $(this).find('[data-field]').each(function(){
            const path = $(this).data('field');
            const type = $(this).data('type') || this.tagName.toLowerCase();
            let val = '';
            
            if (type === 'chips' || type === 'sortable-chips') {
              val = $(this).find('.chip.active').map((i,el) => $(el).text().trim()).get();
            } else if (this.type === 'checkbox') {
              val = !!this.checked;
            } else {
              val = $(this).val();
            }
            
            setPath(data, path, val);
          });
        });
        
        // Add metadata
        data._metadata = {
          completedAt: new Date().toISOString(),
          version: '2.0',
          completionPercentage: calculateCompletionPercentage()
        };
        
        return data;
      }

      function setPath(obj, path, val){
        const parts = path.split('.');
        let o = obj;
        for(let i = 0; i < parts.length - 1; i++){
          if(!o[parts[i]]) o[parts[i]] = {};
          o = o[parts[i]];
        }
        o[parts.at(-1)] = val;
      }

      function populate(data){
        if(!data) return;
        
        $sections.each(function(){
          $(this).find('[data-field]').each(function(){
            const path = $(this).data('field');
            const type = $(this).data('type') || this.tagName.toLowerCase();
            const val = path.split('.').reduce((o,k) => (o ? o[k] : undefined), data);
            
            if(val === undefined) return;
            
            if(type === 'chips' || type === 'sortable-chips'){
              $(this).find('.chip').each(function(){
                if(Array.isArray(val) && val.some(v => $(this).text().trim().includes(v) || v.includes($(this).text().trim()))) {
                  $(this).addClass('active');
                } else {
                  $(this).removeClass('active');
                }
              });
            } else if(this.type === 'checkbox'){
              this.checked = !!val;
            } else {
              $(this).val(val);
            }
          });
        });
        
        updateProgress();
        updateGoalPriorities();
      }

      function calculateCompletionPercentage() {
        const inputs = $("[data-field]");
        const total = inputs.length;
        let filled = 0;
        
        inputs.each(function(){
          const type = $(this).data('type') || this.tagName.toLowerCase();
          
          if(type === 'chips' || type === 'sortable-chips'){
            if($(this).find('.chip.active').length) filled++;
          } else if(this.type === 'checkbox'){
            if(this.checked) filled++;
          } else {
            if(String($(this).val()).trim().length) filled++;
          }
        });
        
        return Math.round((filled / total) * 100);
      }

      function updateProgress(){
        const percentage = calculateCompletionPercentage();
        const inputs = $("[data-field]");
        const filled = inputs.filter(function() {
          const type = $(this).data('type') || this.tagName.toLowerCase();
          if(type === 'chips' || type === 'sortable-chips') return $(this).find('.chip.active').length > 0;
          if(this.type === 'checkbox') return this.checked;
          return String($(this).val()).trim().length > 0;
        }).length;
        
        $progress.text(`${percentage}% complete`);
        $progressFill.css('width', percentage + '%');
        $overallProgress.text(percentage + '%');
        $completedFields.text(filled);
        $totalFields.text(inputs.length);
        
        // Update section counter
        const completedSections = $sections.toArray().filter((_, i) => isSectionComplete(i)).length;
        $sectionCounter.text(`${completedSections}/${$sections.length}`);
        
        // Show completion banner if 100%
        if (percentage === 100) {
          $completionBanner.show();
          $("#btnExport").addClass('button-pulse');
        }
        
        // Budget warning logic
        updateBudgetWarning();
      }

      function updateBudgetWarning() {
        const budget = $('select[data-field="planning.budget"]').val();
        const selectedModules = $('[data-field="features.modules"] .chip.active').length;
        const customFeatures = $('textarea[data-field="features.custom"]').val().trim().length;
        
        let warning = '';
        if (budget === 'under-15k' && (selectedModules > 6 || customFeatures > 100)) {
          warning = 'Selected features may exceed budget range. Consider prioritizing core features.';
        } else if (budget === '15k-25k' && (selectedModules > 10 || customFeatures > 200)) {
          warning = 'Feature scope is ambitious for this budget. We\'ll help prioritize for maximum impact.';
        }
        
        if (warning) {
          $('#budgetAdvice').text(warning);
          $('#budgetWarning').show();
        } else {
          $('#budgetWarning').hide();
        }
      }

      function autosave(){
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
          const data = getData();
          localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
          updateProgress();
          showAutoSaveIndicator();
        }, 500);
      }

      function showAutoSaveIndicator() {
        $autoSaveIndicator.addClass('show');
        setTimeout(() => $autoSaveIndicator.removeClass('show'), 2000);
      }

      // Save / Load / Export with enhanced features
      $("#btnSave").on('click', () => {
        autosave();
        blink('#btnSave');
        showNotification('✅ Progress saved successfully!', 'success');
      });

      $("#btnLoad").on('click', () => {
        const raw = localStorage.getItem(STORAGE_KEY);
        if(!raw) {
          showNotification('❌ No saved data found.', 'warning');
          return;
        }
        
        try {
          const data = JSON.parse(raw);
          populate(data);
          blink('#btnLoad');
          showNotification('📂 Data loaded successfully!', 'success');
        } catch(e) {
          showNotification('❌ Could not load saved data.', 'error');
        }
      });

      $("#btnExport").on('click', () => {
        const data = getData();
        const blob = new Blob([JSON.stringify(data, null, 2)], {type: 'application/json'});
        const a = document.createElement('a');
        const timestamp = new Date().toISOString().slice(0,10);
        a.href = URL.createObjectURL(blob);
        a.download = `bren7-onboarding-${data.org?.name?.replace(/[^a-zA-Z0-9]/g, '-') || 'project'}-${timestamp}.json`;
        a.click();
        URL.revokeObjectURL(a.href);
        
        showNotification('📄 Export downloaded successfully!', 'success');
        $("#btnExport").removeClass('button-pulse');
      });

      // Enhanced modals
      $("#btnSummary").on('click', () => {
        const data = getData();
        openModal('📊 Project Summary', makeSummary(data));
      });

      $("#btnSOW").on('click', () => {
        const data = getData();
        openModal('📋 Statement of Work (Draft)', makeSOW(data));
      });

      $("#btnCollaborate").on('click', () => {
        openCollaborationModal();
      });

      function openModal(title, text){
        $("#modalTitle").text(title);
        $("#summary").text(text);
        $("#modal").addClass('show');
      }

      function closeModal(){
        $("#modal").removeClass('show');
      }

      function openCollaborationModal() {
        const currentUrl = window.location.href;
        const shareUrl = currentUrl + '?shared=' + btoa(JSON.stringify(getData()));
        $('#shareLink').val(shareUrl);
        $('#collaborationModal').addClass('show');
      }

      $("#closeModal, #closeCollabModal").on('click', () => {
        $(".modal").removeClass('show');
      });

      $("#copySummary").on('click', () => {
        const text = $("#summary").text();
        navigator.clipboard.writeText(text).then(() => {
          blink('#copySummary');
          showNotification('📋 Summary copied to clipboard!', 'success');
        });
      });

      $("#copyLink").on('click', () => {
        const link = $("#shareLink").val();
        navigator.clipboard.writeText(link).then(() => {
          blink('#copyLink');
          showNotification('🔗 Link copied to clipboard!', 'success');
        });
      });

      $("#printSummary").on('click', () => window.print());

      $("#emailSummary").on('click', () => {
        const subject = encodeURIComponent('BREN7 CMS Project Summary');
        const body = encodeURIComponent($("#summary").text());
        window.location.href = `mailto:?subject=${subject}&body=${body}`;
      });

      function showNotification(message, type = 'info') {
        const notification = $(`
          <div class="notification ${type}" style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--panel);
            border: 1px solid var(--divider);
            border-radius: 12px;
            padding: 12px 16px;
            box-shadow: var(--shadow);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
          ">
            ${message}
          </div>
        `);
        
        $('body').append(notification);
        setTimeout(() => {
          notification.css('animation', 'slideOutRight 0.3s ease');
          setTimeout(() => notification.remove(), 300);
        }, 3000);
      }

      function blink(sel){
        const $b = $(sel);
        $b.css({outline: '2px solid var(--accent)', transform: 'scale(1.05)'});
        setTimeout(() => $b.css({outline: 'none', transform: 'scale(1)'}), 400);
      }

      // Enhanced summary generation
      function makeSummary(d){
        const lines = [];
        const p = (h, v) => lines.push(`${h}: ${v || '—'}`);
        const join = a => Array.isArray(a) && a.length ? a.join(', ') : '—';

        lines.push(`# ${d.org?.name || 'Organization'} - BREN7 CMS Project Summary`);
        lines.push(`Generated: ${new Date().toLocaleDateString()}`);
        lines.push('');
        
        lines.push('## 🏢 Organization');
        if(d.org) {
          p('Name', d.org.name);
          p('Industry', d.org.industry);
          p('Contact', `${d.org.contact || ''} ${d.org.email ? ' <' + d.org.email + '>' : ''}`.trim());
          p('Current Website', d.org.current_url);
        }
        
        lines.push('');
        lines.push('## 🎯 Project Goals');
        p('Primary Goals', join(d.goals?.list));
        p('Success Metrics', d.goals?.metrics);
        
        lines.push('');
        lines.push('## 👥 Audience & Content');
        p('Target Audiences', d.content?.audiences);
        p('Key User Tasks', d.content?.tasks);
        p('Content Migration', d.content?.migration);
        p('Languages', d.content?.languages);
        
        lines.push('');
        lines.push('## 🎨 Design & Branding');
        p('Brand Guidelines', d.design?.brand);
        p('Design Inspiration', d.design?.examples);
        p('Tone & Personality', join(d.design?.tone));
        p('Accessibility Requirements', d.design?.accessibility);
        
        lines.push('');
        lines.push('## ⚙️ Features & Functionality');
        p('Selected Modules', join(d.features?.modules));
        p('Custom Requirements', d.features?.custom);
        
        lines.push('');
        lines.push('## 🔗 System Integrations');
        p('CRM/Donor Management', d.integrations?.crm);
        p('Email Marketing', d.integrations?.email);
        p('Payment Processing', d.integrations?.payments);
        p('Analytics & Tracking', d.integrations?.analytics);
        
        lines.push('');
        lines.push('## 📈 SEO & Performance');
        p('Target Keywords', d.seo?.keywords);
        p('URL Strategy', d.seo?.urls);
        p('Content Governance', d.seo?.governance);
        p('Performance Targets', d.seo?.performance);
        
        lines.push('');
        lines.push('## 👤 User Management');
        p('Number of Editors', d.users?.count);
        p('Role Structure', d.users?.roles);
        p('Training Preferences', join(d.users?.training));
        p('Approval Workflow', d.users?.workflow);
        
        lines.push('');
        lines.push('## 🔒 Hosting & Security');
        p('Domains', d.hosting?.domains);
        p('Security Requirements', d.hosting?.security);
        p('Environments', join(d.hosting?.env));
        p('SLA Requirements', d.hosting?.sla);
        
        lines.push('');
        lines.push('## 📅 Timeline & Budget');
        p('Target Launch', d.planning?.launch);
        p('Budget Range', d.planning?.budget);
        p('Key Constraints', d.planning?.risks);
        p('Stakeholders', d.planning?.stakeholders);
        
        lines.push('');
        lines.push('## 📊 Analytics & Success');
        p('Key Metrics', d.analytics?.kpis);
        p('Reporting Schedule', join(d.analytics?.cadence));
        p('Dashboard Needs', d.analytics?.dashboards);
        p('A/B Testing', d.analytics?.ab);
        
        lines.push('');
        lines.push('## 📝 Additional Notes');
        p('Special Requirements', d.notes?.freeform);
        
        if (d._metadata) {
          lines.push('');
          lines.push('---');
          lines.push(`Completion: ${d._metadata.completionPercentage}% | Version: ${d._metadata.version}`);
        }
        
        return lines.join('\n');
      }

      function makeSOW(d){
        const t = [];
        const name = d.org?.name || 'Client Organization';
        const launchDate = d.planning?.launch || 'TBD';
        const budget = d.planning?.budget || 'TBD';
        
        t.push(`${name}`);
        t.push('MORWEB CMS WEBSITE PROJECT');
        t.push('Statement of Work (Draft)');
        t.push('');
        t.push('═══════════════════════════════════════════════════════════════');
        t.push('');
        
        t.push('1. 🎯 PROJECT OBJECTIVES');
        const goals = d.goals?.list || [];
        if (goals.length > 0) {
          goals.slice(0, 5).forEach(goal => t.push(`   • ${goal}`));
        } else {
          t.push('   • Modernize website design and functionality');
          t.push('   • Improve user experience and accessibility');
          t.push('   • Streamline content management');
        }
        
        if (d.goals?.metrics) {
          t.push('');
          t.push('   Success Metrics:');
          t.push(`   ${d.goals.metrics}`);
        }
        
        t.push('');
        t.push('2. 📋 SCOPE OF WORK');
        t.push('   Phase 1: Discovery & Planning');
        t.push('   • Stakeholder interviews and requirements gathering');
        t.push('   • Content audit and migration planning');
        t.push('   • Analytics review and goal setting');
        t.push('   • Technical architecture planning');
        t.push('');
        t.push('   Phase 2: Design & User Experience');
        t.push('   • Information architecture and sitemap');
        t.push('   • User journey mapping and wireframes');
        t.push('   • Visual design system and brand integration');
        t.push('   • Responsive design for all devices');
        t.push('   • Accessibility compliance (WCAG 2.2 AA)');
        t.push('');
        t.push('   Phase 3: Development & Configuration');
        t.push('   • BREN7 CMS setup and configuration');
        t.push('   • Custom template development');
        
        const modules = d.features?.modules || [];
        if (modules.length > 0) {
          t.push('   • Module implementation:');
          modules.forEach(module => t.push(`     - ${module}`));
        }
        
        if (d.features?.custom) {
          t.push('   • Custom functionality:');
          t.push(`     ${d.features.custom}`);
        }
        
        t.push('');
        t.push('   Phase 4: Content & Migration');
        if (d.content?.migration) {
          t.push(`   • Content migration: ${d.content.migration}`);

        } else {
          t.push('   • Content migration from existing site');
        }
        t.push('   • SEO optimization and URL structure');
        t.push('   • Image optimization and media library setup');
        t.push('');
        t.push('   Phase 5: Integration & Testing');
        
        const integrations = [];
        if (d.integrations?.crm) integrations.push(`CRM: ${d.integrations.crm}`);
        if (d.integrations?.email) integrations.push(`Email: ${d.integrations.email}`);
        if (d.integrations?.payments) integrations.push(`Payments: ${d.integrations.payments}`);
        if (d.integrations?.analytics) integrations.push(`Analytics: ${d.integrations.analytics}`);
        
        if (integrations.length > 0) {
          t.push('   • Third-party integrations:');
          integrations.forEach(int => t.push(`     - ${int}`));
        }
        
        t.push('   • Quality assurance testing');
        t.push('   • Performance optimization');
        t.push('   • Cross-browser and device testing');
        t.push('   • Security audit and implementation');
        t.push('');
        t.push('   Phase 6: Training & Launch');
        t.push('   • Content management training');
        t.push('   • Documentation and user guides');
        t.push('   • Go-live support and monitoring');
        t.push('   • Post-launch optimization');
        
        t.push('');
        t.push('3. 📊 DELIVERABLES');
        t.push('   • Project discovery report');
        t.push('   • Site architecture and wireframes');
        t.push('   • Visual design mockups and style guide');
        t.push('   • Fully configured BREN7 CMS website');
        t.push('   • Content migration and optimization');
        t.push('   • Integration setup and testing');
        t.push('   • Training materials and documentation');
        t.push('   • Performance and security audit report');
        
        t.push('');
        t.push('4. ⏰ TIMELINE');
        t.push(`   • Project Start: Upon contract signing`);
        t.push(`   • Target Launch: ${launchDate}`);
        t.push('   • Typical Duration: 8-16 weeks (depending on scope)');
        
        if (d.planning?.risks) {
          t.push('');
          t.push('   Dependencies & Risks:');
          t.push(`   ${d.planning.risks}`);
        }
        
        t.push('');
        t.push('5. 💰 INVESTMENT');
        t.push(`   • Estimated Range: ${budget} CAD`);
        t.push('   • Payment Schedule: 50% at start, 25% at design approval, 25% at launch');
        t.push('   • Ongoing BREN7 hosting and support separate');
        
        t.push('');
        t.push('6. 👥 TEAM & RESPONSIBILITIES');
        
        if (d.planning?.stakeholders) {
          t.push('   Client Team:');
          t.push(`   ${d.planning.stakeholders}`);
        }
        
        t.push('');
        t.push('   BREN7 Team:');
        t.push('   • Project Manager (coordination and timeline)');
        t.push('   • UX/UI Designer (design and user experience)');
        t.push('   • Developer (technical implementation)');
        t.push('   • Content Strategist (migration and optimization)');
        t.push('   • QA Specialist (testing and quality assurance)');
        
        t.push('');
        t.push('7. ✅ ACCEPTANCE CRITERIA');
        t.push('   • All functional requirements implemented and tested');
        t.push('   • Design approved and responsive on all devices');
        t.push('   • Content migrated and optimized');
        t.push('   • Integrations configured and tested');
        t.push('   • Performance targets met (loading speed, accessibility)');
        t.push('   • Training completed and documentation provided');
        t.push('   • Client approval and sign-off received');
        
        t.push('');
        t.push('8. 📈 POST-LAUNCH SUPPORT');
        
        const reportingCadence = Array.isArray(d.analytics?.cadence) ? 
          d.analytics.cadence.join(', ') : 
          d.analytics?.cadence || 'Monthly';
          
        t.push(`   • Performance monitoring and reporting: ${reportingCadence}`);
        
        if (d.analytics?.kpis) {
          t.push('   • Key metrics tracking:');
          t.push(`     ${d.analytics.kpis}`);
        }
        
        t.push('   • Ongoing BREN7 platform updates and security');
        t.push('   • Additional training and support available');
        
        t.push('');
        t.push('═══════════════════════════════════════════════════════════════');
        t.push('');
        t.push('This Statement of Work is subject to final scope confirmation');
        t.push('and will be refined based on detailed discovery findings.');
        t.push('');
        t.push(`Generated: ${new Date().toLocaleDateString()}`);
        
        return t.join('\n');
      }

      // Auto capture changes with enhanced debouncing
      $(document).on('input change', '[data-field]', function(){
        const $field = $(this);
        $field.addClass('changed');
        setTimeout(() => $field.removeClass('changed'), 1000);
        autosave();
      });

      // Keyboard shortcuts
      $(document).on('keydown', function(e){
        // Ctrl/Cmd + S: Save
        if((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's'){
          e.preventDefault();
          $("#btnSave").click();
        }
        
        // Ctrl/Cmd + P: Print summary  
        if((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'p'){
          e.preventDefault();
          $("#btnSummary").click();
        }
        
        // Tab + Enter: Next section (when not in input)
        if(e.key === 'Tab' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA'){
          e.preventDefault();
          if(e.shiftKey){
            $("#prev").click();
          } else {
            $("#next").click();
          }
        }
        
        // Escape: Skip optional field or close modal
        if(e.key === 'Escape'){
          if($('.modal.show').length > 0){
            $('.modal').removeClass('show');
          } else if(!$(e.target).closest('.field-group.required').length){
            $("#skip").click();
          }
        }
      });

      // Touch/swipe support for mobile
      let touchStartX = 0;
      let touchEndX = 0;

      $(document).on('touchstart', function(e){
        touchStartX = e.changedTouches[0].screenX;
      });

      $(document).on('touchend', function(e){
        touchEndX = e.changedTouches[0].screenX;
        const swipeThreshold = 100;
        
        if(touchEndX < touchStartX - swipeThreshold){
          // Swipe left - next section
          if(!allMode && currentSection < $sections.length - 1){
            $("#next").click();
          }
        }
        
        if(touchEndX > touchStartX + swipeThreshold){
          // Swipe right - previous section
          if(!allMode && currentSection > 0){
            $("#prev").click();
          }
        }
      });

      // Initialize suggestions
      setupSuggestions();

      // Load saved data or URL parameters
      const urlParams = new URLSearchParams(window.location.search);
      const sharedData = urlParams.get('shared');
      
      if(sharedData){
        try {
          const data = JSON.parse(atob(sharedData));
          populate(data);
          showNotification('📥 Shared form data loaded!', 'info');
        } catch(e) {
          console.error('Could not load shared data');
        }
      } else {
        const saved = localStorage.getItem(STORAGE_KEY);
        if(saved){
          try {
            populate(JSON.parse(saved));
          } catch(e) {
            console.error('Could not load saved data');
          }
        }
      }

      // Initialize
      setActive(Number(localStorage.getItem('bren7_onboarding_idx')) || 0);
      updateProgress();

      // Add CSS animations for notifications
      const style = document.createElement('style');
      style.textContent = `
        @keyframes slideInRight {
          from { transform: translateX(100%); opacity: 0; }
          to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
          from { transform: translateX(0); opacity: 1; }
          to { transform: translateX(100%); opacity: 0; }
        }
        .changed {
          background-color: rgba(90,167,255,.1) !important;
          transition: background-color 0.3s ease;
        }
        @media (max-width: 600px) {
          .notification {
            left: 10px !important;
            right: 10px !important;
            top: 10px !important;
            width: auto !important;
          }
        }
        .ghost {
          opacity: 0.4;
        }
        .sortable-drag {
          transform: rotate(5deg);
          z-index: 1000;
        }
      `;
      document.head.appendChild(style);

      // Advanced features initialization
      
      // Smart field dependencies
      function initializeFieldDependencies() {
        // When donation module is selected, suggest payment integrations
        $(document).on('click', '[data-field="features.modules"] .chip:contains("Donations")', function() {
          if ($(this).hasClass('active') && !$('input[data-field="integrations.payments"]').val()) {
            setTimeout(() => {
              showNotification('💡 Consider adding payment integration for donations!', 'info');
              $('input[data-field="integrations.payments"]').focus().attr('placeholder', 'Stripe recommended for donations');
            }, 1000);
          }
        });

        // When membership module is selected, suggest CRM
        $(document).on('click', '[data-field="features.modules"] .chip:contains("Membership")', function() {
          if ($(this).hasClass('active') && !$('input[data-field="integrations.crm"]').val()) {
            setTimeout(() => {
              showNotification('💡 A CRM integration will help manage members!', 'info');
              $('input[data-field="integrations.crm"]').focus();
            }, 1000);
          }
        });

        // Budget change triggers feature review
        $('select[data-field="planning.budget"]').on('change', function() {
          setTimeout(updateBudgetWarning, 500);
        });
      }

      // Performance monitoring
      function initializePerformanceTracking() {
        const startTime = Date.now();
        
        // Track section completion times
        let sectionTimes = {};
        let lastSectionTime = startTime;
        
        function trackSectionTime(sectionIndex) {
          const now = Date.now();
          const timeSpent = now - lastSectionTime;
          sectionTimes[sectionIndex] = timeSpent;
          lastSectionTime = now;
          
          // Store analytics data
          const analytics = JSON.parse(localStorage.getItem('bren7_analytics') || '{}');
          analytics.sectionTimes = sectionTimes;
          analytics.totalTime = now - startTime;
          localStorage.setItem('bren7_analytics', JSON.stringify(analytics));
        }

        // Hook into section changes
        const originalSetActive = setActive;
        window.setActive = function(idx) {
          trackSectionTime(currentSection);
          return originalSetActive(idx);
        };
      }

      // Advanced validation
      function initializeAdvancedValidation() {
        // Email validation
        $('input[type="email"]').on('blur', function() {
          const email = $(this).val();
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          
          if (email && !emailRegex.test(email)) {
            $(this).css('border-color', 'var(--danger)');
            showNotification('❌ Please enter a valid email address', 'error');
          } else if (email) {
            $(this).css('border-color', 'var(--success)');
          }
        });

        // URL validation
        $('input[type="url"]').on('blur', function() {
          const url = $(this).val();
          try {
            if (url && !url.startsWith('http')) {
              $(this).val('https://' + url);
            }
            if (url) {
              new URL($(this).val());
              $(this).css('border-color', 'var(--success)');
            }
          } catch (e) {
            if (url) {
              $(this).css('border-color', 'var(--danger)');
              showNotification('❌ Please enter a valid URL', 'error');
            }
          }
        });

        // Phone number formatting
        $('input[data-field="org.phone"]').on('input', function() {
          let value = $(this).val().replace(/\D/g, '');
          if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            $(this).val(value);
          }
        });
      }

      // Context-aware help system
      function initializeContextualHelp() {
        // Show relevant tips based on current section
        const tips = {
          0: "💡 Pro tip: Use your official legal name for the organization field.",
          1: "🎯 Focus on 3-5 primary goals - you can always add more later!",
          2: "👥 Think about your top 3 visitor types and what they need most.",
          3: "🎨 Collect 2-3 example sites you love before this meeting.",
          4: "⚙️ Start with essential features - you can expand functionality over time.",
          5: "🔗 Integrations save time but add complexity - prioritize your must-haves.",
          6: "📈 Good SEO takes time - focus on 5-10 realistic target keywords.",
          7: "👤 Plan for who will actually maintain the site day-to-day.",
          8: "🔒 BREN7 handles most security - focus on your specific requirements.",
          9: "📅 Add 2-4 weeks buffer to your ideal launch date for best results.",
          10: "📊 Pick 3-5 key metrics that directly relate to your goals.",
          11: "📝 This is your space to mention anything unique about your situation."
        };

        function showContextualTip(sectionIndex) {
          if (tips[sectionIndex]) {
            setTimeout(() => {
              showNotification(tips[sectionIndex], 'info');
            }, 2000);
          }
        }

        // Show tip when entering new section
        const originalSetActive = window.setActive || setActive;
        window.setActive = function(idx) {
          const result = originalSetActive(idx);
          showContextualTip(idx);
          return result;
        };
      }

      // Auto-completion suggestions based on what's already filled
      function initializeSmartSuggestions() {
        // Suggest goals based on industry
        $('input[data-field="org.industry"]').on('change blur', function() {
          const industry = $(this).val().toLowerCase();
          let suggestedGoals = [];
          
          if (industry.includes('non-profit') || industry.includes('charity')) {
            suggestedGoals = ['Increase online donations', 'Recruit volunteers effectively', 'Improve accessibility (WCAG 2.2 AA)'];
          } else if (industry.includes('municipality') || industry.includes('government')) {
            suggestedGoals = ['Improve accessibility (WCAG 2.2 AA)', 'Better mobile experience', 'Simplify content editing workflow'];
          } else if (industry.includes('association')) {
            suggestedGoals = ['Grow membership base', 'Drive event registrations', 'Integrate with existing systems'];
          }
          
          if (suggestedGoals.length > 0) {
            setTimeout(() => {
              showNotification(`💡 Suggested goals for ${industry}: ${suggestedGoals.join(', ')}`, 'info');
            }, 1000);
          }
        });
      }

      // Export enhancements
      function initializeAdvancedExports() {
        // PDF export (simplified version)
        window.exportToPDF = function() {
          const data = getData();
          const summary = makeSummary(data);
          
          // Create a new window with print-friendly formatting
          const printWindow = window.open('', '_blank');
          printWindow.document.write(`
            <html>
              <head>
                <title>${data.org?.name || 'Organization'} - BREN7 Project Summary</title>
                <style>
                  body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                  h1, h2 { color: #1e40af; }
                  pre { white-space: pre-wrap; font-family: inherit; }
                </style>
              </head>
              <body>
                <pre>${summary}</pre>
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
          `);
          printWindow.document.close();
          printWindow.print();
        };

        // Add PDF export button
        $('#btnExport').after('<button id="btnPDF" class="button">📄 Export PDF</button>');
        $('#btnPDF').on('click', window.exportToPDF);
      }

      // Progress persistence across sessions
      function initializeProgressPersistence() {
        // Save progress every 30 seconds
        setInterval(() => {
          if (calculateCompletionPercentage() > 0) {
            const data = getData();
            data._sessionData = {
              lastActive: new Date().toISOString(),
              currentSection: currentSection,
              timeSpent: Date.now() - (window.sessionStartTime || Date.now())
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
          }
        }, 30000);

        // Welcome back message for returning users
        const savedData = localStorage.getItem(STORAGE_KEY);
        if (savedData) {
          try {
            const data = JSON.parse(savedData);
            if (data._sessionData && data._sessionData.lastActive) {
              const lastActive = new Date(data._sessionData.lastActive);
              const daysSince = Math.floor((Date.now() - lastActive.getTime()) / (1000 * 60 * 60 * 24));
              
              if (daysSince > 0) {
                setTimeout(() => {
                  showNotification(`👋 Welcome back! You last worked on this ${daysSince} day${daysSince > 1 ? 's' : ''} ago.`, 'info');
                }, 1000);
              }
            }
          } catch (e) {
            console.error('Could not parse session data');
          }
        }
      }

      // Integration testing
      function initializeIntegrationTesting() {
        // Simple integration validation
        $('input[data-field^="integrations."]').on('blur', function() {
          const value = $(this).val().toLowerCase();
          const field = $(this).data('field');
          
          // Check for common integration compatibility issues
          if (field === 'integrations.crm' && value.includes('salesforce')) {
            setTimeout(() => {
              showNotification('ℹ️ Salesforce integration may require additional API setup time', 'info');
            }, 500);
          }
          
          if (field === 'integrations.payments' && value.includes('paypal')) {
            setTimeout(() => {
              showNotification('ℹ️ PayPal works well but Stripe often provides better donor experience', 'info');
            }, 500);
          }
        });
      }

      // Accessibility enhancements
      function initializeAccessibilityFeatures() {
        // High contrast mode toggle
        const contrastToggle = $('<button class="button-ghost" style="font-size: 12px;">🌓 High Contrast</button>');
        $('.header-actions').prepend(contrastToggle);
        
        contrastToggle.on('click', function() {
          $('body').toggleClass('high-contrast');
          $(this).text($('body').hasClass('high-contrast') ? '🌞 Normal' : '🌓 High Contrast');
        });

        // Add high contrast CSS
        const contrastCSS = `
          .high-contrast {
            --bg: #000000 !important;
            --panel: #1a1a1a !important;
            --text: #ffffff !important;
            --muted: #cccccc !important;
            --divider: #444444 !important;
          }
          .high-contrast .chip.active {
            background: #ffffff !important;
            color: #000000 !important;
          }
        `;
        
        $('<style>').text(contrastCSS).appendTo('head');

        // Screen reader announcements for progress
        const srAnnouncer = $('<div aria-live="polite" class="sr-only" style="position: absolute; left: -10000px;"></div>');
        $('body').append(srAnnouncer);
        
        function announceProgress() {
          const percentage = calculateCompletionPercentage();
          srAnnouncer.text(`Form ${percentage}% complete`);
        }

        // Announce progress changes
        const originalUpdateProgress = updateProgress;
        window.updateProgress = function() {
          originalUpdateProgress();
          announceProgress();
        };
      }

      // Initialize all advanced features
      window.sessionStartTime = Date.now();
      
      initializeFieldDependencies();
      initializePerformanceTracking();
      initializeAdvancedValidation();
      initializeContextualHelp();
      initializeSmartSuggestions();
      initializeAdvancedExports();
      initializeProgressPersistence();
      initializeIntegrationTesting();
      initializeAccessibilityFeatures();

      // Final initialization message
      setTimeout(() => {
        showNotification('🚀 Enhanced BREN7 form loaded! Hover over ? icons for help.', 'success');
      }, 1000);

      console.log('🎉 BREN7 Enhanced Onboarding Form v2.0 - Fully Loaded!');
      console.log('Features: Smart suggestions, drag-drop prioritization, auto-save, collaboration, advanced analytics');
      console.log('Shortcuts: Ctrl+S (save), Ctrl+P (summary), Tab+Enter (next), Esc (skip)');

    })();

    // jQuery document ready backup
    $(document).ready(function() {
      // Fallback initialization if needed
      if (!window.setActive) {
        console.warn('Primary initialization may have failed, running backup...');
        // Could add fallback initialization here if needed
      }
    });
  </script>
</body>
</html>