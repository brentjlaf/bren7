<!DOCTYPE html>
<html lang="en">
<head>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trakster Beat Maker</title>

  <!-- SEO Meta Tags -->
  <meta name="description" content="Trakster is a free web-based beat maker that lets you build custom drum beats in seconds. Create, tweak, and download your loops with ease.">
  <meta name="keywords" content="Trakster, beat maker, online drum machine, free beat tool, create beats, drum sequencer, loop creator">
  <meta name="author" content="Brent Jlaf">

  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="Trakster Beat Maker">
  <meta property="og:description" content="Make beats in your browser with Trakster — fast, free, and no sign-up required.">
  <meta property="og:image" content="https://bren7.com/Trakster/Trakster-Logo.png">
  <meta property="og:url" content="https://bren7.com/Trakster/trakster-beat-maker.php">
  <meta property="og:type" content="website">

  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Trakster Beat Maker">
  <meta name="twitter:description" content="Build your beats instantly with Trakster – an intuitive, browser-based drum machine.">
  <meta name="twitter:image" content="https://bren7.com/Trakster/Trakster-Logo.png">

  <!-- Favicon -->
  <link rel="icon" href="https://bren7.com/favicon.png" type="image/x-icon">

  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1RGGXKCNB6"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-1RGGXKCNB6');
  </script>

  <!-- jQuery & jQuery UI CSS/JS -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

	
  <!-- jQuery & jQuery UI CSS/JS -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      background: #1e1e1e;
      color: #e0e0e0;
      font-family: Arial, sans-serif;
    }
    .container {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    /* Header Nav */
    header {
      background: #2e2e2e;
      box-shadow: 0 2px 4px rgba(0,0,0,0.5);
      padding: 0 20px;
    }
    nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 60px;
    }
    .logo {
      font-size: 1.5em;
      font-weight: bold;
    }
    .header-controls button {
      background: #3a3a3a;
      color: #e0e0e0;
      border: none;
      outline: none;
      border-radius: 4px;
      padding: 8px 16px;
      font-size: 14px;
      cursor: pointer;
      margin-left: 10px;
      transition: background 0.3s ease;
    }
    .header-controls button:hover {
      background: #00bfff;
    }

    /* Main content wrapper */
    .content-wrapper {
      flex: 1;
      display: flex;
      gap: 20px;
      padding: 10px;
      overflow: auto;
    }
    /* Sidebars */
    .sidebar {
      background: #2e2e2e;
      padding: 15px;
      border-radius: 8px;
      width: 250px;
      flex-shrink: 0;
      box-shadow: 0 2px 4px rgba(0,0,0,0.5);
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .sidebar h2 {
      margin-bottom: 10px;
      font-size: 1.3em;
      border-bottom: 1px solid #444;
      padding-bottom: 5px;
    }
    /* Left Controls, Right Controls */
    .left-controls, .right-controls {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .left-controls label, .right-controls label {
      font-size: 14px;
    }
    .left-controls select,
    .left-controls button,
    .left-controls input[type="range"],
    .right-controls button {
      background: #3a3a3a;
      color: #e0e0e0;
      border: none;
      outline: none;
      border-radius: 4px;
      padding: 8px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .left-controls button:hover,
    .right-controls button:hover {
      background: #00bfff;
    }

    /* Main Content: The Step Grid */
    .main-content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      overflow: auto;
    }
    .step-grid {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed; /* Helps with responsive columns */
      box-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    .step-grid tbody tr {
      height: 40px;
    }
    /* First column for instrument icons */
    .step-grid td.instrument-icon {
      width: 40px;
      text-align: center;
      background: #2e2e2e;
      border: 1px solid #444;
    }
    /* Step cells */
    .step-grid td.step-cell {
      border: 1px solid #444;
      background: #3a3a3a;
      text-align: center;
      vertical-align: middle;
      cursor: pointer;
      position: relative;
      transition: background 0.2s;
    }
    .step-grid td.step-cell.on {
      /* Default "on" color; each instrument gets its own override. */
      background: #00bfff;
    }
    /* Playback highlight */
    .step-grid td.step-cell.current {
      outline: 2px solid #fffa00;
      outline-offset: -2px;
    }

    /* Different "on" colors per instrument */
    .step-grid td.step-cell.on.kick { background: #f44336; }     /* Red */
    .step-grid td.step-cell.on.snare { background: #3f51b5; }    /* Indigo */
    .step-grid td.step-cell.on.hihat { background: #4caf50; }    /* Green */
    .step-grid td.step-cell.on.clap { background: #ff9800; }     /* Orange */
    .step-grid td.step-cell.on.tom { background: #9c27b0; }      /* Purple */
    .step-grid td.step-cell.on.cowbell { background: #ffc107; }  /* Amber */

    /* Footer Link (recorded file) */
    #downloadLink a {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 12px;
      background: #00bfff;
      color: #1e1e1e;
      text-decoration: none;
      border-radius: 4px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .content-wrapper {
        flex-direction: column;
      }
      .sidebar {
        width: auto;
      }
      .step-grid tbody tr {
        height: 30px;
      }
      .step-grid td.instrument-icon {
        width: 30px;
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
  <!-- Header with Nav -->
  <header>
    <nav>
      <div class="logo"><img src="Trakster-Logo.png" style="width:190px" /></div>
      <div class="header-controls">
        <button id="play">Play</button>
        <button id="stop" disabled>Stop</button>
      </div>
    </nav>
  </header>

  <div class="content-wrapper">
    <!-- Left Sidebar -->
    <div class="sidebar left-sidebar">
      <h2>Settings</h2>
      <div class="left-controls">
        <label for="preset-select">Preset Beat:</label>
        <select id="preset-select">
          <option value="">-- Select a Preset --</option>
          <option value="presetA">Preset A</option>
          <option value="presetB">Preset B</option>
          <option value="presetC">Preset C</option>
          <option value="presetD">Preset D</option>
          <option value="presetE">Preset E</option>
          <option value="presetF">Preset F</option>
          <option value="presetG">Preset G</option>
          <option value="presetH">Preset H</option>
        </select>

        <button id="reset">Reset Timeline</button>

        <label for="tempo-slider">Tempo (BPM):</label>
        <input type="range" id="tempo-slider" min="30" max="180" value="90">
        <span id="tempo-value">90 BPM</span>

        <label for="volume-slider">Volume:</label>
        <input type="range" id="volume-slider" min="0" max="100" value="100">
        <span id="volume-value">100%</span>
      </div>
    </div>

    <!-- Center: Step Grid (6 instruments x 16 steps + 1 icon column) -->
    <div class="main-content">
      <table class="step-grid">
        <tbody>
          <!-- Kick -->
          <tr data-instrument="kick">
            <td class="instrument-icon"><i class="fas fa-drum"></i></td>
            <!-- 16 steps -->
            <td class="step-cell" data-step="0"></td><td class="step-cell" data-step="1"></td><td class="step-cell" data-step="2"></td><td class="step-cell" data-step="3"></td>
            <td class="step-cell" data-step="4"></td><td class="step-cell" data-step="5"></td><td class="step-cell" data-step="6"></td><td class="step-cell" data-step="7"></td>
            <td class="step-cell" data-step="8"></td><td class="step-cell" data-step="9"></td><td class="step-cell" data-step="10"></td><td class="step-cell" data-step="11"></td>
            <td class="step-cell" data-step="12"></td><td class="step-cell" data-step="13"></td><td class="step-cell" data-step="14"></td><td class="step-cell" data-step="15"></td>
          </tr>
          <!-- Snare -->
          <tr data-instrument="snare">
            <td class="instrument-icon"><i class="fas fa-drumstick-bite"></i></td>
            <td class="step-cell" data-step="0"></td><td class="step-cell" data-step="1"></td><td class="step-cell" data-step="2"></td><td class="step-cell" data-step="3"></td>
            <td class="step-cell" data-step="4"></td><td class="step-cell" data-step="5"></td><td class="step-cell" data-step="6"></td><td class="step-cell" data-step="7"></td>
            <td class="step-cell" data-step="8"></td><td class="step-cell" data-step="9"></td><td class="step-cell" data-step="10"></td><td class="step-cell" data-step="11"></td>
            <td class="step-cell" data-step="12"></td><td class="step-cell" data-step="13"></td><td class="step-cell" data-step="14"></td><td class="step-cell" data-step="15"></td>
          </tr>
          <!-- Hi-Hat -->
          <tr data-instrument="hihat">
            <td class="instrument-icon"><i class="fas fa-ellipsis-h"></i></td>
            <td class="step-cell" data-step="0"></td><td class="step-cell" data-step="1"></td><td class="step-cell" data-step="2"></td><td class="step-cell" data-step="3"></td>
            <td class="step-cell" data-step="4"></td><td class="step-cell" data-step="5"></td><td class="step-cell" data-step="6"></td><td class="step-cell" data-step="7"></td>
            <td class="step-cell" data-step="8"></td><td class="step-cell" data-step="9"></td><td class="step-cell" data-step="10"></td><td class="step-cell" data-step="11"></td>
            <td class="step-cell" data-step="12"></td><td class="step-cell" data-step="13"></td><td class="step-cell" data-step="14"></td><td class="step-cell" data-step="15"></td>
          </tr>
          <!-- Clap -->
          <tr data-instrument="clap">
            <td class="instrument-icon"><i class="fas fa-hands-clapping"></i></td>
            <td class="step-cell" data-step="0"></td><td class="step-cell" data-step="1"></td><td class="step-cell" data-step="2"></td><td class="step-cell" data-step="3"></td>
            <td class="step-cell" data-step="4"></td><td class="step-cell" data-step="5"></td><td class="step-cell" data-step="6"></td><td class="step-cell" data-step="7"></td>
            <td class="step-cell" data-step="8"></td><td class="step-cell" data-step="9"></td><td class="step-cell" data-step="10"></td><td class="step-cell" data-step="11"></td>
            <td class="step-cell" data-step="12"></td><td class="step-cell" data-step="13"></td><td class="step-cell" data-step="14"></td><td class="step-cell" data-step="15"></td>
          </tr>
          <!-- Tom -->
          <tr data-instrument="tom">
            <td class="instrument-icon"><i class="fas fa-drum"></i></td>
            <td class="step-cell" data-step="0"></td><td class="step-cell" data-step="1"></td><td class="step-cell" data-step="2"></td><td class="step-cell" data-step="3"></td>
            <td class="step-cell" data-step="4"></td><td class="step-cell" data-step="5"></td><td class="step-cell" data-step="6"></td><td class="step-cell" data-step="7"></td>
            <td class="step-cell" data-step="8"></td><td class="step-cell" data-step="9"></td><td class="step-cell" data-step="10"></td><td class="step-cell" data-step="11"></td>
            <td class="step-cell" data-step="12"></td><td class="step-cell" data-step="13"></td><td class="step-cell" data-step="14"></td><td class="step-cell" data-step="15"></td>
          </tr>
          <!-- Cowbell -->
          <tr data-instrument="cowbell">
            <td class="instrument-icon"><i class="fas fa-bell"></i></td>
            <td class="step-cell" data-step="0"></td><td class="step-cell" data-step="1"></td><td class="step-cell" data-step="2"></td><td class="step-cell" data-step="3"></td>
            <td class="step-cell" data-step="4"></td><td class="step-cell" data-step="5"></td><td class="step-cell" data-step="6"></td><td class="step-cell" data-step="7"></td>
            <td class="step-cell" data-step="8"></td><td class="step-cell" data-step="9"></td><td class="step-cell" data-step="10"></td><td class="step-cell" data-step="11"></td>
            <td class="step-cell" data-step="12"></td><td class="step-cell" data-step="13"></td><td class="step-cell" data-step="14"></td><td class="step-cell" data-step="15"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Right Sidebar -->
    <div class="sidebar right-sidebar">
      <h2>Recording</h2>
      <div class="right-controls">
        <button id="record">Record Beat</button>
        <button id="randomBeat">Random Beat</button>
        <div id="downloadLink"></div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // ============== AUDIO CONTEXT / MASTER GAIN ==============
  const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
  const masterGain = audioCtx.createGain();
  masterGain.gain.value = 1;
  masterGain.connect(audioCtx.destination);

  // For Recording
  const dest = audioCtx.createMediaStreamDestination();
  masterGain.connect(dest);

  // ============== DRUM SOUND FUNCTIONS ==============
  function playKick() {
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.frequency.setValueAtTime(150, audioCtx.currentTime);
    osc.frequency.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
    gain.gain.setValueAtTime(1, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
    osc.connect(gain);
    gain.connect(masterGain);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.5);
  }

  function playSnare() {
    const bufferSize = audioCtx.sampleRate;
    const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
    const data = buffer.getChannelData(0);
    for (let i = 0; i < bufferSize; i++) {
      data[i] = Math.random() * 2 - 1;
    }
    const noise = audioCtx.createBufferSource();
    noise.buffer = buffer;
    const gain = audioCtx.createGain();
    gain.gain.setValueAtTime(1, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.2);
    noise.connect(gain);
    gain.connect(masterGain);
    noise.start();
    noise.stop(audioCtx.currentTime + 0.2);
  }

  function playHiHat() {
    const bufferSize = audioCtx.sampleRate;
    const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
    const data = buffer.getChannelData(0);
    for (let i = 0; i < bufferSize; i++) {
      data[i] = Math.random() * 2 - 1;
    }
    const noise = audioCtx.createBufferSource();
    noise.buffer = buffer;
    const filter = audioCtx.createBiquadFilter();
    filter.type = "highpass";
    filter.frequency.value = 7000;
    const gain = audioCtx.createGain();
    gain.gain.setValueAtTime(0.7, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.05);
    noise.connect(filter);
    filter.connect(gain);
    gain.connect(masterGain);
    noise.start();
    noise.stop(audioCtx.currentTime + 0.05);
  }

  function playClap() {
    const bufferSize = audioCtx.sampleRate;
    const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
    const data = buffer.getChannelData(0);
    for (let i = 0; i < bufferSize; i++) {
      data[i] = (Math.random() * 2 - 1) * 0.5;
    }
    const noise = audioCtx.createBufferSource();
    noise.buffer = buffer;
    const gain = audioCtx.createGain();
    gain.gain.setValueAtTime(1, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
    noise.connect(gain);
    gain.connect(masterGain);
    noise.start();
    noise.stop(audioCtx.currentTime + 0.15);
  }

  function playTom() {
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.frequency.setValueAtTime(100, audioCtx.currentTime);
    osc.frequency.exponentialRampToValueAtTime(50, audioCtx.currentTime + 0.3);
    gain.gain.setValueAtTime(1, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
    osc.connect(gain);
    gain.connect(masterGain);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.3);
  }

  // A simple beep for cowbell
  function playCowbell() {
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    // Somewhat higher frequency
    osc.frequency.setValueAtTime(550, audioCtx.currentTime);
    // Quick decay
    gain.gain.setValueAtTime(0.6, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.15);
    osc.connect(gain);
    gain.connect(masterGain);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.15);
  }

  // Helper to play the correct sound
  function playInstrument(instr) {
    switch(instr) {
      case 'kick': playKick(); break;
      case 'snare': playSnare(); break;
      case 'hihat': playHiHat(); break;
      case 'clap': playClap(); break;
      case 'tom': playTom(); break;
      case 'cowbell': playCowbell(); break;
      default: break;
    }
  }

  // ============== UI: TOGGLING CELLS ==============
  // Click a step cell => toggle on/off, add instrument class for color
  $('.step-cell').on('click', function() {
    const $cell = $(this);
    const $row = $cell.closest('tr');
    const instrument = $row.data('instrument');

    if ($cell.hasClass('on')) {
      // Turn off
      $cell.removeClass(`on ${instrument}`);
    } else {
      // Turn on
      $cell.addClass(`on ${instrument}`);
    }
  });

  // ============== PRESETS (A–H) ==============
  // Each preset now includes 'cowbell'
  const presets = {
    presetA: {
      kick:    [true,false,false,false, true,false,false,false, true,false,false,false, true,false,false,false],
      snare:   [false,false,false,false, true,false,false,false, false,false,false,false, true,false,false,false],
      hihat:   [true,false,true,false, true,false,true,false, true,false,true,false, true,false,true,false],
      clap:    [false,false,false,false, false,false,false,false, false,false,false,false, false,false,false,false],
      tom:     [false,false,false,false, false,false,false,false, false,false,true,false, false,false,false,false],
      cowbell: [false,false,false,false, false,false,false,false, false,false,false,false, false,false,false,false]
    },
    presetB: {
      kick:    [true,false,false,true, false,false,true,false, true,false,false,true, false,false,true,false],
      snare:   [false,false,true,false, false,true,false,false, true,false,true,false, false,true,false,false],
      hihat:   Array(16).fill(true),
      clap:    [false,false,false,false, true,false,false,false, false,false,false,false, true,false,false,false],
      tom:     Array(16).fill(false),
      cowbell: Array(16).fill(false)
    },
    presetC: {
      kick:    [true,false,true,false, false,false,true,false, true,false,false,false, true,false,true,false],
      snare:   [false,false,false,true, false,false,false,true, false,false,false,true, false,false,false,true],
      hihat:   [false,true,false,true, false,true,false,true, false,true,false,true, false,true,false,true],
      clap:    [false,false,true,false, false,true,false,false, true,false,false,true, false,false,false,false],
      tom:     [false,false,false,false, true,false,false,false, false,false,true,false, false,false,false,false],
      cowbell: Array(16).fill(false)
    },
    presetD: {
      kick:    [true,false,false,false, true,false,true,false, true,false,false,false, true,false,false,false],
      snare:   [false,false,true,false, false,false,false,true, false,false,true,false, false,false,true,false],
      hihat:   Array(16).fill(true),
      clap:    [false,false,false,false, false,false,false,false, false,true,false,false, false,false,false,false],
      tom:     [false,false,false,false, false,false,false,false, false,false,true,false, false,false,false,false],
      cowbell: Array(16).fill(false)
    },
    presetE: {
      kick:    [true,false,false,true, false,false,true,false, false,true,false,false, true,false,false,true],
      snare:   [false,false,true,false, false,true,false,false, true,false,false,true, false,true,false,false],
      hihat:   Array(16).fill(true),
      clap:    Array(16).fill(false),
      tom:     Array(16).fill(false),
      cowbell: Array(16).fill(false)
    },
    presetF: {
      kick:    [true,false,true,false, true,false,true,false, true,false,true,false, true,false,true,false],
      snare:   [false,false,false,true, false,false,false,true, false,false,false,true, false,false,false,true],
      hihat:   [true,true,false,true, true,true,false,true, true,true,false,true, true,true,false,true],
      clap:    [false,false,false,false, false,true,false,false, false,false,false,false, false,true,false,false],
      tom:     [false,false,false,false, true,false,false,false, false,false,false,false, true,false,false,false],
      cowbell: Array(16).fill(false)
    },
    presetG: {
      kick:    [true,false,false,false, true,false,false,false, true,false,false,false, true,false,false,false],
      snare:   [false,true,false,false, false,true,false,false, false,true,false,false, false,true,false,false],
      hihat:   Array(16).fill(true),
      clap:    Array(16).fill(false),
      tom:     Array(16).fill(false),
      cowbell: Array(16).fill(false)
    },
    presetH: {
      kick:    [true,false,true,false, true,false,false,true, true,false,true,false, true,false,false,true],
      snare:   [false,false,false,true, false,false,false,true, false,false,false,true, false,false,false,true],
      hihat:   [true,false,true,false, true,false,true,false, true,false,true,false, true,false,true,false],
      clap:    [false,false,false,false, true,false,false,false, false,false,false,false, true,false,false,false],
      tom:     [false,false,false,false, false,false,true,false, false,false,false,false, false,false,false,false],
      cowbell: [false,false,false,false, false,false,false,false, false,false,false,false, false,false,false,false]
    }
  };

  // Apply a preset to the DOM
  function loadPreset(presetName) {
    if (!presets[presetName]) return;
    // Clear existing toggles
    $('.step-cell').removeClass('on kick snare hihat clap tom cowbell');

    const preset = presets[presetName];
    // For each instrument row
    $('tr[data-instrument]').each(function() {
      const instrument = $(this).data('instrument');
      const pattern = preset[instrument];
      if (pattern) {
        // Only look at step cells
        $(this).find('td.step-cell').each(function(idx) {
          if (pattern[idx]) {
            $(this).addClass(`on ${instrument}`);
          }
        });
      }
    });
  }

  $('#preset-select').on('change', function() {
    const presetName = $(this).val();
    loadPreset(presetName);
  });

  // Reset Timeline
  $('#reset').click(function() {
    $('.step-cell').removeClass('on kick snare hihat clap tom cowbell');
  });

  // ============== RANDOM BEAT GENERATOR ==============
  function generateRandomBeat() {
    // Clear everything first
    $('.step-cell').removeClass('on kick snare hihat clap tom cowbell');

    $('tr[data-instrument]').each(function() {
      const instrument = $(this).data('instrument');
      let pattern = [];
      for (let i = 0; i < 16; i++) {
        let hit = false;
        if (instrument === "hihat") {
          // Give hi-hat a steady pattern
          hit = (i % 2 === 0);
        } else if (instrument === "kick") {
          hit = (Math.random() < 0.4);
        } else if (instrument === "snare") {
          hit = (Math.random() < 0.4);
        } else if (instrument === "clap") {
          hit = (Math.random() < 0.2);
        } else if (instrument === "tom") {
          hit = (Math.random() < 0.1);
        } else if (instrument === "cowbell") {
          hit = (Math.random() < 0.1);
        }
        pattern.push(hit);
      }
      // Ensure at least one hit for kick & snare
      if ((instrument === "kick" || instrument === "snare") && !pattern.includes(true)) {
        pattern[Math.floor(Math.random() * 16)] = true;
      }
      // Apply to DOM
      $(this).find('td.step-cell').each(function(idx) {
        if (pattern[idx]) {
          $(this).addClass(`on ${instrument}`);
        }
      });
    });
  }
  $('#randomBeat').click(function() {
    generateRandomBeat();
  });

  // ============== PLAYBACK LOGIC ==============
  const totalSteps = 16;
  let currentStep = 0;
  let playInterval = null;
  let bpm = 90;
  let stepDuration = 60000 / (bpm * 4);

  function highlightStep(step) {
    // Remove highlight from all
    $('.step-cell').removeClass('current');
    // Add highlight to cells that match the current step
    $(`.step-cell[data-step="${step}"]`).addClass('current');
  }

  function playStep(step) {
    // For each instrument row, check if that step is on
    $('tr[data-instrument]').each(function() {
      const instrument = $(this).data('instrument');
      const cell = $(this).find(`.step-cell[data-step="${step}"]`);
      if (cell.hasClass('on')) {
        // Play the corresponding sound
        playInstrument(instrument);
      }
    });
  }

  function startPlayback() {
    currentStep = 0;
    highlightStep(currentStep);
    playStep(currentStep);
    playInterval = setInterval(function() {
      currentStep = (currentStep + 1) % totalSteps;
      highlightStep(currentStep);
      playStep(currentStep);
    }, stepDuration);
  }

  function stopPlayback() {
    clearInterval(playInterval);
    playInterval = null;
    $('.step-cell').removeClass('current');
  }

  $('#play').click(function() {
    startPlayback();
    $(this).prop("disabled", true);
    $('#stop').prop("disabled", false);
  });

  $('#stop').click(function() {
    stopPlayback();
    $(this).prop("disabled", true);
    $('#play').prop("disabled", false);
  });

  // ============== TEMPO & VOLUME ==============
  $('#tempo-slider').on('input change', function() {
    bpm = $(this).val();
    $('#tempo-value').text(bpm + ' BPM');
    stepDuration = 60000 / (bpm * 4);
    if (playInterval !== null) {
      clearInterval(playInterval);
      playInterval = setInterval(function() {
        currentStep = (currentStep + 1) % totalSteps;
        highlightStep(currentStep);
        playStep(currentStep);
      }, stepDuration);
    }
  });

  $('#volume-slider').on('input change', function() {
    let volume = $(this).val();
    $('#volume-value').text(volume + '%');
    masterGain.gain.value = volume / 100;
  });

  // ============== RECORDING LOGIC ==============
  let mediaRecorder;
  let recordedChunks = [];

  $('#record').click(function() {
    if (!mediaRecorder || mediaRecorder.state === 'inactive') {
      recordedChunks = [];
      mediaRecorder = new MediaRecorder(dest.stream, { mimeType: "audio/webm" });
      mediaRecorder.ondataavailable = function(e) {
        if (e.data.size > 0) {
          recordedChunks.push(e.data);
        }
      };
      mediaRecorder.onstop = function() {
        const blob = new Blob(recordedChunks, { type: "audio/webm" });
        const url = URL.createObjectURL(blob);
        $("#downloadLink").html(`<a href="${url}" download="beat.webm">Download Beat (WebM)</a>`);
      };
      mediaRecorder.start();
      $(this).text("Stop Recording");
    } else {
      mediaRecorder.stop();
      $(this).text("Record Beat");
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
