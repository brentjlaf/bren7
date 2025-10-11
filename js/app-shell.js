(function () {
  const APP_SHELL_ATTRIBUTE = 'data-app-shell-id';

  function addStylesheet(href, id) {
    if (!document.head) {
      return;
    }

    if (document.querySelector(`link[${APP_SHELL_ATTRIBUTE}="${id}"]`)) {
      return;
    }

    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    link.setAttribute(APP_SHELL_ATTRIBUTE, id);
    document.head.appendChild(link);
  }

  function addPreconnect(href, id) {
    if (!document.head) {
      return;
    }
    if (document.querySelector(`link[rel="preconnect"][${APP_SHELL_ATTRIBUTE}="${id}"]`)) {
      return;
    }
    const link = document.createElement('link');
    link.rel = 'preconnect';
    link.href = href;
    link.setAttribute(APP_SHELL_ATTRIBUTE, id);
    document.head.appendChild(link);
  }

  function ensureHeadAssets() {
    addPreconnect('https://fonts.googleapis.com', 'app-shell-preconnect-fonts');
    addPreconnect('https://fonts.gstatic.com', 'app-shell-preconnect-fonts-static');

    addStylesheet('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&family=Raleway:wght@400;700&display=swap', 'app-shell-fonts');
    addStylesheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', 'app-shell-fa');
    addStylesheet('/css/style.css?v=appshell', 'app-shell-core');
    addStylesheet('/css/app-shell.css?v=2', 'app-shell-theme');
  }

  function createHeader() {
    const header = document.createElement('header');
    header.className = 'app-header projects-header app-shell-header';

    header.innerHTML = `
      <div class="header-content">
        <div class="logo-wrapper">
          <div class="logo">BREN<span class="accent">7</span></div>
          <div class="logo-underline"></div>
        </div>
        <p class="tagline">Web Tools &amp; Experiments</p>
        <nav class="primary-nav" aria-label="Primary">
          <a href="/" class="nav-link"><i class="fas fa-home"></i> Home</a>
          <a href="/projects.php" class="nav-link"><i class="fas fa-th-large"></i> Projects</a>
        </nav>
      </div>
    `;

    return header;
  }

  function createFooter() {
    const footer = document.createElement('footer');
    footer.className = 'app-shell-footer';
    footer.innerHTML = `
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
    `;

    return footer;
  }

  function wrapContent() {
    const body = document.body;

    if (!body || body.dataset.appShellReady === 'true') {
      return;
    }

    body.dataset.appShellReady = 'true';
    body.classList.add('projects-page', 'app-shell-body');

    const contentNodes = [];
    const preservedScripts = [];

    body.childNodes.forEach((node) => {
      if (node.nodeType === Node.ELEMENT_NODE) {
        const element = node;
        const isAppShellScript =
          element.tagName === 'SCRIPT' &&
          element.getAttribute('src') &&
          element.getAttribute('src').includes('/js/app-shell.js');

        if (isAppShellScript) {
          preservedScripts.push(element);
          return;
        }
      }

      contentNodes.push(node);
    });

    const gridBackground = document.createElement('div');
    gridBackground.className = 'grid-background';

    const mainWrapper = document.createElement('div');
    mainWrapper.className = 'main-wrapper app-shell-wrapper';

    const main = document.createElement('main');
    main.className = 'projects-main app-shell-main';

    const contentSection = document.createElement('section');
    contentSection.className = 'app-shell-content';

    contentNodes.forEach((node) => {
      if (node.parentNode === body) {
        contentSection.appendChild(node);
      } else {
        contentSection.appendChild(node);
      }
    });

    main.appendChild(contentSection);
    mainWrapper.appendChild(createHeader());
    mainWrapper.appendChild(main);
    mainWrapper.appendChild(createFooter());

    body.innerHTML = '';
    body.appendChild(gridBackground);
    body.appendChild(mainWrapper);
    preservedScripts.forEach((script) => body.appendChild(script));

    const currentYearEl = document.getElementById('current-year');
    if (currentYearEl) {
      currentYearEl.textContent = new Date().getFullYear();
    }
  }

  ensureHeadAssets();

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', wrapContent);
  } else {
    wrapContent();
  }
})();
