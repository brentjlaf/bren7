<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Flexbox CSS Generator – Advanced Layout Builder</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Control complex Flexbox properties, visualize item behavior, and export CSS using the advanced generator from BREN7.">
  <meta name="keywords" content="advanced flexbox generator, css layout playground, flex properties editor, responsive flexbox tool, layout visualizer">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Flexbox CSS Generator – Advanced Layout Builder">
  <meta property="og:description" content="Simulate flex containers, tweak advanced options, and copy CSS instantly with BREN7's Flexbox generator.">
  <meta property="og:url" content="https://bren7.com/apps/flexbox-generator-advanced.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Flexbox CSS Generator – Advanced Layout Builder">
  <meta name="twitter:description" content="Preview advanced Flexbox behaviors and export CSS with BREN7's generator.">
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

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <style>
    :root{
      --accent: #ff6b35;
      --accent-2:#ff3d71;
      --bg:#141414;
      --panel:#212121;
      --panel-2:#2b2b2b;
      --ink:#e8e8e8;
      --ink-dim:#a9a9a9;
      --stroke:#3a3a3a;
    }
    *{box-sizing:border-box}
    body{
      margin:0; background:linear-gradient(135deg,var(--bg),#0f0f13);
      color:var(--ink); font-family:Montserrat,system-ui,Arial,sans-serif;
      padding:24px;
    }
    .container{max-width:1200px;margin:0 auto}
    .header{
      padding:22px;border-radius:16px;margin-bottom:24px;
      background:linear-gradient(135deg,var(--accent),var(--accent-2));
      box-shadow:0 14px 40px rgba(0,0,0,.35);
      text-align:center
    }
    .header h1{margin:0;font-weight:800;letter-spacing:.5px;text-shadow:0 6px 20px rgba(0,0,0,.25)}
    .grid{
      display:grid;gap:18px;grid-template-columns:1.2fr .8fr;
    }
    @media (max-width:980px){ .grid{grid-template-columns:1fr} }

    .panel{
      background:var(--panel); border:1px solid var(--stroke);
      border-radius:14px; padding:18px;
    }
    .panel h3{margin:0 0 12px;font-size:1.05rem;color:var(--accent)}
    .row{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px}
    .label{font-size:.88rem;color:var(--ink-dim);min-width:120px}
    .btn,.seg .opt, select, input[type="number"], input[type="text"]{
      background:#2a2a2a;border:1px solid var(--stroke);color:var(--ink);
      border-radius:10px;padding:10px 12px;font:inherit;cursor:pointer
    }
    .btn{background:linear-gradient(135deg,var(--accent),var(--accent-2));border:none}
    .btn:active{transform:translateY(1px)}
    .seg{display:flex;gap:8px;flex-wrap:wrap}
    .seg .opt{cursor:pointer;user-select:none}
    .seg .opt.active{background:linear-gradient(135deg,var(--accent),var(--accent-2));border-color:transparent}
    .inline{display:flex;gap:10px;align-items:center}
    .num{width:110px}
    .wide{flex:1;min-width:160px}
    .switch{display:flex;gap:8px;align-items:center}
    .switch input{width:20px;height:20px}

    /* Preview */
    .preview-wrap{background:var(--panel-2);border:1px dashed var(--stroke);border-radius:14px;padding:16px}
    #flexPreview{
      background:#1a1a1a;border:1px solid var(--stroke);
      border-radius:12px; height:280px; width:100%; overflow:auto;
      position:relative
    }
    .item{
      display:flex;align-items:center;justify-content:center;
      color:white;border-radius:10px;font-weight:600;min-height:60px;
      border:1px solid rgba(255,255,255,.08);
      box-shadow:0 6px 16px rgba(0,0,0,.25);
    }

    /* Code boxes */
    .codegrid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    @media (max-width:980px){ .codegrid{grid-template-columns:1fr} }
    .codebox{
      background:#111;border:1px solid var(--stroke); border-radius:12px;
      padding:14px;font-family:ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
      font-size:.92rem;white-space:pre;overflow:auto;min-height:160px;cursor:pointer
    }
    .codebox:hover{outline:1px solid var(--accent)}
    .help{font-size:.86rem;color:var(--ink-dim);margin-top:6px}

    /* Presets */
    .presets{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px}
    .preset{
      border:1px solid var(--stroke);border-radius:12px;padding:12px;cursor:pointer;
      background:#1b1b1b; transition:transform .15s ease, border-color .15s ease
    }
    .preset:hover{transform:translateY(-2px);border-color:var(--accent)}
    .mini{height:80px;background:#0f0f0f;border:1px dashed var(--stroke);border-radius:10px;display:flex;gap:6px;padding:6px;overflow:hidden}
    .mini > div{flex:0 0 24%;background:linear-gradient(135deg,var(--accent),var(--accent-2));border-radius:6px}
    .preset h4{margin:8px 0 2px;font-size:.95rem}
    .preset p{margin:0;color:var(--ink-dim);font-size:.82rem}

    /* Overrides table */
    #overridesPanel{display:none;margin-top:10px}
    table{width:100%;border-collapse:collapse;font-size:.9rem}
    th,td{border-bottom:1px solid var(--stroke);padding:8px 6px;text-align:left}
    th{color:var(--ink-dim);font-weight:600}
    td input, td select{width:100%}
    .notice{font-size:.85rem;color:var(--ink-dim);margin:6px 0 0}
    .note{opacity:.9}

    .toast{
      position:fixed;right:18px;top:18px;background:linear-gradient(135deg,var(--accent),var(--accent-2));
      color:#fff;padding:12px 14px;border-radius:10px;display:none;z-index:9999;box-shadow:0 12px 28px rgba(0,0,0,.35)
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
    <div class="header"><h1>Flexbox CSS Generator</h1></div>

    <div class="grid">
      <!-- LEFT: Controls -->
      <div class="panel">
        <h3>Container</h3>
        <div class="row">
          <div class="label">Direction</div>
          <div class="seg" id="dirSeg">
            <div class="opt active" data-v="row">row</div>
            <div class="opt" data-v="row-reverse">row-reverse</div>
            <div class="opt" data-v="column">column</div>
            <div class="opt" data-v="column-reverse">column-reverse</div>
          </div>
        </div>

        <div class="row">
          <div class="label">Wrap</div>
          <div class="seg" id="wrapSeg">
            <div class="opt active" data-v="wrap">wrap</div>
            <div class="opt" data-v="nowrap">nowrap</div>
            <div class="opt" data-v="wrap-reverse">wrap-reverse</div>
          </div>
        </div>

        <div class="row">
          <div class="label">Justify</div>
          <select id="justify" class="wide">
            <option>flex-start</option><option>center</option><option>flex-end</option>
            <option>space-between</option><option>space-around</option><option>space-evenly</option>
          </select>
        </div>

        <div class="row">
          <div class="label">Align Items</div>
          <select id="alignItems" class="wide">
            <option>stretch</option><option>flex-start</option><option>center</option><option>flex-end</option><option>baseline</option>
          </select>
        </div>

        <div class="row">
          <div class="label">Align Content</div>
          <select id="alignContent" class="wide">
            <option>stretch</option><option>flex-start</option><option>center</option><option>flex-end</option><option>space-between</option><option>space-around</option>
          </select>
        </div>

        <div class="row">
          <div class="label">Gap (px)</div>
          <div class="inline">
            <input id="gap" type="number" class="num" value="12" min="0" step="1"/>
            <div class="label">Padding (px)</div>
            <input id="pad" type="number" class="num" value="12" min="0" step="1"/>
            <div class="label">Height (px)</div>
            <input id="height" type="number" class="num" value="280" min="120" step="10"/>
          </div>
        </div>

        <hr style="border-color:var(--stroke);opacity:.5;margin:14px 0">

        <h3>Items</h3>
        <div class="row">
          <div class="label">Count</div>
          <div class="inline">
            <input id="count" type="number" class="num" value="6" min="1" max="24" step="1"/>
            <label class="switch">
              <input id="colorful" type="checkbox" checked />
              <span>Colorful boxes</span>
            </label>
          </div>
        </div>

        <div class="row">
          <div class="label">Flex (grow / shrink / basis)</div>
          <div class="inline wide">
            <input id="grow" type="number" class="num" value="0" min="0" step="1" />
            <input id="shrink" type="number" class="num" value="1" min="0" step="1" />
            <input id="basis" type="text" class="wide" value="120px" />
          </div>
        </div>

        <div class="row">
          <div class="label">Align Self</div>
          <select id="alignSelf" class="wide">
            <option>auto</option><option>stretch</option><option>flex-start</option><option>center</option><option>flex-end</option><option>baseline</option>
          </select>
        </div>

        <div class="row">
          <button id="toggleOverrides" class="btn" type="button">Per-item overrides</button>
        </div>

        <div id="overridesPanel" class="panel" style="padding:10px;margin-top:10px">
          <h3 style="margin-bottom:8px">Overrides</h3>
          <div class="notice">Tip: leave fields blank to use the global item values.</div>
          <div class="note help">Edits here reflect live and will be included in the CSS output via <code>:nth-child()</code> rules.</div>
          <div id="ovrTableWrap" style="margin-top:10px"></div>
        </div>
      </div>

      <!-- RIGHT: Preview + Code -->
      <div class="panel">
        <h3>Preview</h3>
        <div class="preview-wrap">
          <div id="flexPreview" aria-label="Flex container preview"></div>
        </div>

        <div style="display:flex;gap:10px;margin:14px 0 8px">
          <button id="copyHTML" class="btn" type="button">Copy HTML</button>
          <button id="copyCSS" class="btn" type="button">Copy CSS</button>
          <button id="copyBoth" class="btn" type="button">Copy Both</button>
        </div>

        <div class="codegrid">
          <div>
            <h3>HTML (click to copy)</h3>
            <div id="htmlOut" class="codebox" title="Click to copy HTML"></div>
            <div class="help">Adds <code>.flex-container</code> and <code>.item</code> elements.</div>
          </div>
          <div>
            <h3>CSS (click to copy)</h3>
            <div id="cssOut" class="codebox" title="Click to copy CSS"></div>
            <div class="help">Includes container, base item rules, and any per-item overrides.</div>
          </div>
        </div>

        <h3 style="margin-top:18px">Presets</h3>
        <div class="presets" id="presets"></div>
      </div>
    </div>
  </div>

  <div class="toast" id="toast">Copied to clipboard!</div>

  <script>
    $(function(){
      const state = {
        container:{
          direction:'row',
          wrap:'wrap',
          justify:'flex-start',
          alignItems:'stretch',
          alignContent:'stretch',
          gap:12,
          padding:12,
          height:280
        },
        items:{
          count:6,
          grow:0,
          shrink:1,
          basis:'120px',
          alignSelf:'auto',
          colorful:true
        },
        overrides:{} // index -> {order?, grow?, shrink?, basis?, alignSelf?}
      };

      const $preview = $('#flexPreview');
      const $htmlOut = $('#htmlOut');
      const $cssOut = $('#cssOut');

      // ---------- UI wiring ----------
      seg($('#dirSeg'), 'direction');
      seg($('#wrapSeg'), 'wrap');

      $('#justify').val(state.container.justify).on('change', e => setC('justify', e.target.value));
      $('#alignItems').val(state.container.alignItems).on('change', e => setC('alignItems', e.target.value));
      $('#alignContent').val(state.container.alignContent).on('change', e => setC('alignContent', e.target.value));
      $('#gap').val(state.container.gap).on('input', e => setC('gap', int(e.target.value)));
      $('#pad').val(state.container.padding).on('input', e => setC('padding', int(e.target.value)));
      $('#height').val(state.container.height).on('input', e => setC('height', int(e.target.value)));

      $('#count').val(state.items.count).on('input', e => { state.items.count = clamp(int(e.target.value),1,24); buildItems(); buildOverridesTable(); push(); });
      $('#colorful').prop('checked', state.items.colorful).on('change', e => { state.items.colorful = e.target.checked; paintItems(); });
      $('#grow').val(state.items.grow).on('input', e => { state.items.grow = int(e.target.value); push(); });
      $('#shrink').val(state.items.shrink).on('input', e => { state.items.shrink = int(e.target.value); push(); });
      $('#basis').val(state.items.basis).on('input', e => { state.items.basis = e.target.value || 'auto'; push(); });
      $('#alignSelf').val(state.items.alignSelf).on('change', e => { state.items.alignSelf = e.target.value; push(); });

      $('#toggleOverrides').on('click', () => $('#overridesPanel').slideToggle(160));

      $('#copyHTML').on('click', () => copy($htmlOut.text()));
      $('#copyCSS').on('click', () => copy($cssOut.text()));
      $('#copyBoth').on('click', () => copy($htmlOut.text() + '\n\n' + $cssOut.text()));
      $htmlOut.on('click', () => copy($htmlOut.text()));
      $cssOut.on('click', () => copy($cssOut.text()));

      // Presets
      const PRESETS = [
        {
          name:'Centered Row',
          note:'Row • nowrap • centered both',
          apply: () => {
            setMany({
              direction:'row', wrap:'nowrap', justify:'center', alignItems:'center', alignContent:'center',
              gap:16, padding:16, height:260
            }, {count:3, grow:0, shrink:1, basis:'140px', alignSelf:'auto'});
          }
        },
        {
          name:'Toolbar / Space-Between',
          note:'Row • nowrap • space-between',
          apply: () => {
            setMany({
              direction:'row', wrap:'nowrap', justify:'space-between', alignItems:'center', alignContent:'stretch',
              gap:10, padding:14, height:120
            }, {count:4, grow:0, shrink:1, basis:'auto', alignSelf:'auto'});
          }
        },
        {
          name:'Card Grid (wrap)',
          note:'Row • wrap • equal cards',
          apply: () => {
            setMany({
              direction:'row', wrap:'wrap', justify:'flex-start', alignItems:'stretch', alignContent:'stretch',
              gap:14, padding:14, height:280
            }, {count:8, grow:0, shrink:1, basis:'200px', alignSelf:'auto'});
          }
        },
        {
          name:'Sidebar + Content',
          note:'Row • nowrap • first fixed, second flex',
          apply: () => {
            setMany({
              direction:'row', wrap:'nowrap', justify:'flex-start', alignItems:'stretch', alignContent:'stretch',
              gap:14, padding:14, height:260
            }, {count:2, grow:0, shrink:1, basis:'220px', alignSelf:'auto'});
            // overrides: item1 fixed, item2 grows
            state.overrides = {
              0: {grow:0, shrink:0, basis:'220px'},
              1: {grow:1, shrink:1, basis:'auto'}
            };
            buildOverridesTable();
          }
        },
        {
          name:'Vertical Stack',
          note:'Column • gap • centered',
          apply: () => {
            setMany({
              direction:'column', wrap:'nowrap', justify:'center', alignItems:'stretch', alignContent:'stretch',
              gap:12, padding:14, height:320
            }, {count:4, grow:0, shrink:1, basis:'auto', alignSelf:'auto'});
          }
        },
        {
          name:'Evenly Spaced',
          note:'Row • wrap • space-evenly',
          apply: () => {
            setMany({
              direction:'row', wrap:'wrap', justify:'space-evenly', alignItems:'center', alignContent:'space-evenly',
              gap:10, padding:12, height:260
            }, {count:6, grow:0, shrink:1, basis:'120px', alignSelf:'auto'});
          }
        }
      ];
      buildPresets();

      // ---------- Helpers ----------
      function seg($root, key){
        $root.on('click','.opt', function(){
          $root.find('.opt').removeClass('active');
          $(this).addClass('active');
          setC(key, $(this).data('v'));
        });
      }
      function setC(k,v){ state.container[k]=v; push(); }
      function setMany(c, i){
        Object.assign(state.container, c);
        Object.assign(state.items, i);
        // UI sync
        $('#dirSeg .opt').removeClass('active').filter(`[data-v="${state.container.direction}"]`).addClass('active');
        $('#wrapSeg .opt').removeClass('active').filter(`[data-v="${state.container.wrap}"]`).addClass('active');
        $('#justify').val(state.container.justify);
        $('#alignItems').val(state.container.alignItems);
        $('#alignContent').val(state.container.alignContent);
        $('#gap').val(state.container.gap);
        $('#pad').val(state.container.padding);
        $('#height').val(state.container.height);
        $('#count').val(state.items.count);
        $('#grow').val(state.items.grow);
        $('#shrink').val(state.items.shrink);
        $('#basis').val(state.items.basis);
        $('#alignSelf').val(state.items.alignSelf);
        buildItems();
        push();
      }
      function int(v){ return parseInt(v||0,10) }
      function clamp(n,min,max){ return Math.max(min, Math.min(max, n)) }

      function buildItems(){
        const n = state.items.count;
        $preview.empty();
        for(let i=0;i<n;i++){
          const $d = $('<div class="item"></div>').text(i+1);
          $preview.append($d);
        }
        paintItems();
      }

      function paintItems(){
        const n = state.items.count;
        $preview.children().each(function(i){
          const c = state.items.colorful
            ? `hsl(${Math.round((360/n)*i)}, 70%, 52%)`
            : '#2f2f2f';
          $(this).css({
            background:c
          });
        });
        push();
      }

      function applyContainerStyles(){
        $preview.css({
          display:'flex',
          flexDirection:state.container.direction,
          flexWrap:state.container.wrap,
          justifyContent:state.container.justify,
          alignItems:state.container.alignItems,
          alignContent:state.container.alignContent,
          gap: state.container.gap + 'px',
          padding: state.container.padding + 'px',
          height: state.container.height + 'px'
        });
      }

      function applyItemStyles(){
        const base = {
          flexGrow: state.items.grow,
          flexShrink: state.items.shrink,
          flexBasis: state.items.basis,
          alignSelf: state.items.alignSelf
        };
        $preview.children().each(function(i){
          const o = state.overrides[i] || {};
          $(this).css({
            order: o.order ?? '',
            flexGrow: o.grow ?? base.flexGrow,
            flexShrink: o.shrink ?? base.flexShrink,
            flexBasis: o.basis ?? base.flexBasis,
            alignSelf: o.alignSelf ?? base.alignSelf
          });
        });
      }

      function buildHTML(){
        const lines = [];
        lines.push('<div class="flex-container">');
        for(let i=0;i<state.items.count;i++){
          lines.push(`  <div class="item">${i+1}</div>`);
        }
        lines.push('</div>');
        return lines.join('\n');
      }

      function buildCSS(){
        const c = state.container;
        const it = state.items;
        const lines = [];
        lines.push('/* Container */');
        lines.push('.flex-container {');
        lines.push('  display: flex;');
        lines.push(`  flex-direction: ${c.direction};`);
        lines.push(`  flex-wrap: ${c.wrap};`);
        lines.push(`  justify-content: ${c.justify};`);
        lines.push(`  align-items: ${c.alignItems};`);
        lines.push(`  align-content: ${c.alignContent};`);
        lines.push(`  gap: ${c.gap}px;`);
        lines.push(`  padding: ${c.padding}px;`);
        lines.push('}');
        lines.push('');
        lines.push('/* Items (base) */');
        lines.push('.flex-container .item {');
        lines.push(`  flex: ${it.grow} ${it.shrink} ${it.basis};`);
        lines.push(`  align-self: ${it.alignSelf};`);
        lines.push('  /* Optional demo look (remove in production) */');
        lines.push('  color: #fff;');
        lines.push('  border-radius: 10px;');
        lines.push('  text-align: center;');
        lines.push('  padding: 18px;');
        lines.push('}');
        // Per-item overrides via :nth-child
        const per = Object.keys(state.overrides);
        if(per.length){
          lines.push('');
          lines.push('/* Per-item overrides */');
          per.forEach(k=>{
            const idx = Number(k)+1;
            const o = state.overrides[k];
            const rules = [];
            if(o.order !== undefined && o.order !== '') rules.push(`order: ${o.order};`);
            if(o.grow  !== undefined && o.grow  !== '') rules.push(`flex-grow: ${o.grow};`);
            if(o.shrink!== undefined && o.shrink!== '') rules.push(`flex-shrink: ${o.shrink};`);
            if(o.basis !== undefined && o.basis !== '') rules.push(`flex-basis: ${o.basis};`);
            if(o.alignSelf !== undefined && o.alignSelf !== '') rules.push(`align-self: ${o.alignSelf};`);
            if(rules.length){
              lines.push(`.flex-container .item:nth-child(${idx}) { ${rules.join(' ')} }`);
            }
          });
        }
        return lines.join('\n');
      }

      function push(){
        applyContainerStyles();
        applyItemStyles();
        $htmlOut.text(buildHTML());
        $cssOut.text(buildCSS());
      }

      function copy(text){
        navigator.clipboard.writeText(text).then(showToast).catch(()=>{
          const ta = document.createElement('textarea');
          ta.value = text; document.body.appendChild(ta);
          ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
          showToast();
        });
      }
      function showToast(){ $('#toast').fadeIn(120).delay(1200).fadeOut(200); }

      function buildPresets(){
        const $p = $('#presets').empty();
        PRESETS.forEach(p=>{
          const $card = $(`
            <div class="preset">
              <div class="mini"></div>
              <h4>${p.name}</h4>
              <p>${p.note}</p>
            </div>
          `);
          // Tiny preview visualization
          const $mini = $card.find('.mini');
          $mini.css({display:'flex',flexWrap:'wrap',justifyContent:'space-between',alignItems:'center'});
          for(let i=0;i<4;i++) $mini.append('<div></div>');
          $card.on('click', ()=>{ p.apply(); push(); });
          $p.append($card);
        });
      }

      function buildOverridesTable(){
        const n = state.items.count;
        const $wrap = $('#ovrTableWrap').empty();
        const $t = $('<table><thead><tr><th>#</th><th>order</th><th>grow</th><th>shrink</th><th>basis</th><th>align-self</th></tr></thead><tbody></tbody></table>');
        for(let i=0;i<n;i++){
          const o = state.overrides[i] || {};
          const row = $(`
            <tr>
              <td>${i+1}</td>
              <td><input data-i="${i}" data-k="order" type="number" placeholder=""/></td>
              <td><input data-i="${i}" data-k="grow" type="number" placeholder=""/></td>
              <td><input data-i="${i}" data-k="shrink" type="number" placeholder=""/></td>
              <td><input data-i="${i}" data-k="basis" type="text" placeholder=""/></td>
              <td>
                <select data-i="${i}" data-k="alignSelf">
                  <option value=""></option>
                  <option>auto</option><option>stretch</option><option>flex-start</option>
                  <option>center</option><option>flex-end</option><option>baseline</option>
                </select>
              </td>
            </tr>
          `);
          row.find('[data-k="order"]').val(valOr(o.order));
          row.find('[data-k="grow"]').val(valOr(o.grow));
          row.find('[data-k="shrink"]').val(valOr(o.shrink));
          row.find('[data-k="basis"]').val(valOr(o.basis));
          row.find('[data-k="alignSelf"]').val(valOr(o.alignSelf));
          $t.find('tbody').append(row);
        }
        $wrap.append($t);

        // listen

        $wrap.on('input change','input,select', function(){
          const i = Number($(this).data('i'));
          const k = $(this).data('k');
          const v = $(this).val();
          if(!state.overrides[i]) state.overrides[i] = {};
          // empty clears override
          if(v === ''){
            delete state.overrides[i][k];
            // if empty object, remove it
            if(Object.keys(state.overrides[i]).length===0) delete state.overrides[i];
          }else{
            // numeric for order/grow/shrink when applicable
            state.overrides[i][k] = (k==='grow' || k==='shrink' || k==='order') ? Number(v) : v;
          }
          push();
        });

        function valOr(v){ return (v===undefined || v==='') ? '' : v; }
      }

      // init
      buildItems();
      buildOverridesTable();
      push();
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
