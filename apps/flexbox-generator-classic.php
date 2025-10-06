<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flexbox CSS Generator – Classic Layout Builder</title>
  <!-- SEO Meta Tags -->
  <meta name="description" content="Experiment with container and item settings to generate production-ready Flexbox CSS using the classic builder from BREN7.">
  <meta name="keywords" content="flexbox generator, css layout tool, flex container settings, responsive layout builder, flexbox playground">
  <meta name="author" content="Brent">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:title" content="Flexbox CSS Generator – Classic Layout Builder">
  <meta property="og:description" content="Adjust alignment, direction, and gaps to preview Flexbox layouts and copy CSS instantly.">
  <meta property="og:url" content="https://bren7.com/apps/flexbox-generator-classic.php">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://bren7.com/images/favicon.jpg">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="Flexbox CSS Generator – Classic Layout Builder">
  <meta name="twitter:description" content="Fine-tune Flexbox properties and grab CSS with BREN7's classic generator.">
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #1a1a1a;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* header */
        .header {
            background: linear-gradient(135deg, #667eea, #764ba2, #667eea);
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

        /* main layout */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        /* controls section */
        .controls-section {
            background-color: #2a2a2a;
            padding: 30px;
            border-radius: 15px;
        }

        .control-group {
            margin-bottom: 25px;
        }

        .control-group h3 {
            margin-bottom: 15px;
            font-size: 1.2rem;
            color: #667eea;
            border-bottom: 1px solid #404040;
            padding-bottom: 8px;
        }

        /* flex container controls */
        .property-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .property-btn {
            background-color: #404040;
            border: 2px solid #555;
            border-radius: 8px;
            padding: 8px 12px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .property-btn:hover {
            background-color: #555;
        }

        .property-btn.active {
            background-color: #667eea;
            border-color: #667eea;
        }

        /* item controls */
        .item-controls {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .item-controls h4 {
            margin-bottom: 15px;
            color: #764ba2;
        }

        .item-input {
            background-color: #404040;
            border: 2px solid #555;
            border-radius: 8px;
            padding: 8px 12px;
            color: white;
            font-size: 0.9rem;
            width: 80px;
            outline: none;
            margin-right: 10px;
        }

        .item-input:focus {
            border-color: #667eea;
        }

        .add-item-btn, .remove-item-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            color: white;
            cursor: pointer;
            font-size: 0.9rem;
            transition: transform 0.2s;
            margin: 5px;
        }

        .add-item-btn:hover, .remove-item-btn:hover {
            transform: translateY(-2px);
        }

        .remove-item-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        /* preview section */
        .preview-section {
            background-color: #2a2a2a;
            padding: 30px;
            border-radius: 15px;
        }

        .preview-section h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.3rem;
            color: #667eea;
        }

        .flex-preview {
            background-color: #f0f0f0;
            border: 2px solid #555;
            border-radius: 10px;
            padding: 20px;
            height: 300px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .flex-item {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            min-width: 50px;
            min-height: 50px;
            transition: all 0.3s;
        }

        .flex-item:hover {
            transform: scale(1.05);
        }

        /* code sections */
        .code-section {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .code-section h4 {
            margin-bottom: 15px;
            color: #667eea;
        }

        .code-output {
            background-color: #1a1a1a;
            border: 2px solid #555;
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #fff;
            white-space: pre-wrap;
            word-break: break-all;
            cursor: pointer;
            transition: border-color 0.3s;
            max-height: 200px;
            overflow-y: auto;
        }

        .code-output:hover {
            border-color: #667eea;
        }

        /* presets */
        .presets-section {
            grid-column: 1 / -1;
            background-color: #2a2a2a;
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
        }

        .presets-section h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.3rem;
            color: #667eea;
        }

        .presets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .preset-item {
            background-color: #333;
            border: 2px solid #555;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .preset-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .preset-preview {
            background-color: #f0f0f0;
            height: 80px;
            border-radius: 8px;
            display: flex;
            gap: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .preset-preview .mini-item {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 4px;
            min-width: 20px;
            min-height: 20px;
        }

        .preset-name {
            text-align: center;
            font-size: 0.9rem;
            color: #ccc;
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
        @media (max-width: 1024px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .property-buttons {
                gap: 5px;
            }
            
            .property-btn {
                padding: 6px 10px;
                font-size: 0.8rem;
            }

            .flex-preview {
                height: 200px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Flexbox CSS Generator</h1>
        </div>

        <div class="main-layout">
            <div class="controls-section">
                <div class="control-group">
                    <h3>Flex Direction</h3>
                    <div class="property-buttons">
                        <div class="property-btn active" data-property="flexDirection" data-value="row">row</div>
                        <div class="property-btn" data-property="flexDirection" data-value="row-reverse">row-reverse</div>
                        <div class="property-btn" data-property="flexDirection" data-value="column">column</div>
                        <div class="property-btn" data-property="flexDirection" data-value="column-reverse">column-reverse</div>
                    </div>
                </div>

                <div class="control-group">
                    <h3>Justify Content</h3>
                    <div class="property-buttons">
                        <div class="property-btn active" data-property="justifyContent" data-value="flex-start">flex-start</div>
                        <div class="property-btn" data-property="justifyContent" data-value="center">center</div>
                        <div class="property-btn" data-property="justifyContent" data-value="flex-end">flex-end</div>
                        <div class="property-btn" data-property="justifyContent" data-value="space-between">space-between</div>
                        <div class="property-btn" data-property="justifyContent" data-value="space-around">space-around</div>
                        <div class="property-btn" data-property="justifyContent" data-value="space-evenly">space-evenly</div>
                    </div>
                </div>

                <div class="control-group">
                    <h3>Align Items</h3>
                    <div class="property-buttons">
                        <div class="property-btn active" data-property="alignItems" data-value="stretch">stretch</div>
                        <div class="property-btn" data-property="alignItems" data-value="flex-start">flex-start</div>
                        <div class="property-btn" data-property="alignItems" data-value="center">center</div>
                        <div class="property-btn" data-property="alignItems" data-value="flex-end">flex-end</div>
                        <div class="property-btn" data-property="alignItems" data-value="baseline">baseline</div>
                    </div>
                </div>

                <div class="control-group">
                    <h3>Flex Wrap</h3>
                    <div class="property-buttons">
                        <div class="property-btn active" data-property="flexWrap" data-value="nowrap">nowrap</div>
                        <div class="property-btn" data-property="flexWrap" data-value="wrap">wrap</div>
                        <div class="property-btn" data-property="flexWrap" data-value="wrap-reverse">wrap-reverse</div>
                    </div>
                </div>

                <div class="control-group">
                    <h3>Align Content</h3>
                    <div class="property-buttons">
                        <div class="property-btn active" data-property="alignContent" data-value="stretch">stretch</div>
                        <div class="property-btn" data-property="alignContent" data-value="flex-start">flex-start</div>
                        <div class="property-btn" data-property="alignContent" data-value="center">center</div>
                        <div class="property-btn" data-property="alignContent" data-value="flex-end">flex-end</div>
                        <div class="property-btn" data-property="alignContent" data-value="space-between">space-between</div>
                        <div class="property-btn" data-property="alignContent" data-value="space-around">space-around</div>
                    </div>
                </div>

                <div class="control-group">
                    <h3>Gap</h3>
                    <input type="number" id="gapInput" class="item-input" value="10" min="0" max="100">
                    <span>px</span>
                </div>

                <div class="control-group">
                    <h3>Flex Items</h3>
                    <div id="flexItems">
                        <!-- Items will be added here -->
                    </div>
                    <button class="add-item-btn" id="addItemBtn">Add Item</button>
                </div>
            </div>

            <div class="preview-section">
                <h3>Preview</h3>
                <div class="flex-preview" id="flexPreview">
                    <!-- Flex items will be rendered here -->
                </div>

                <div class="code-section">
                    <h4>HTML Code (Click to Copy)</h4>
                    <div class="code-output" id="htmlOutput"></div>
                </div>

                <div class="code-section">
                    <h4>CSS Code (Click to Copy)</h4>
                    <div class="code-output" id="cssOutput"></div>
                </div>
            </div>
        </div>

        <div class="presets-section">
            <h3>Preset Layouts</h3>
            <div class="presets-grid" id="presetsGrid"></div>
        </div>
    </div>

    <div class="notification" id="notification">Code copied to clipboard!</div>

    <script>
        $(document).ready(function() {
            let flexProperties = {
                flexDirection: 'row',
                justifyContent: 'flex-start',
                alignItems: 'stretch',
                flexWrap: 'nowrap',
                alignContent: 'stretch',
                gap: 10
            };

            let flexItems = [
                { flex: '1', alignSelf: 'auto', order: 0 },
                { flex: '1', alignSelf: 'auto', order: 0 },
                { flex: '1', alignSelf: 'auto', order: 0 }
            ];

            const presets = [
                {
                    name: 'Navigation Bar',
                    properties: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'nowrap', alignContent: 'stretch', gap: 20 },
                    items: [{ flex: '0 0 auto' }, { flex: '1' }, { flex: '0 0 auto' }]
                },
                {
                    name: 'Card Layout',
                    properties: { flexDirection: 'row', justifyContent: 'center', alignItems: 'stretch', flexWrap: 'wrap', alignContent: 'flex-start', gap: 20 },
                    items: [{ flex: '1 1 300px' }, { flex: '1 1 300px' }, { flex: '1 1 300px' }]
                },
                {
                    name: 'Sidebar Layout',
                    properties: { flexDirection: 'row', justifyContent: 'flex-start', alignItems: 'stretch', flexWrap: 'nowrap', alignContent: 'stretch', gap: 0 },
                    items: [{ flex: '0 0 200px' }, { flex: '1' }]
                },
                {
                    name: 'Centered Content',
                    properties: { flexDirection: 'column', justifyContent: 'center', alignItems: 'center', flexWrap: 'nowrap', alignContent: 'stretch', gap: 20 },
                    items: [{ flex: '0 0 auto' }]
                },
                {
                    name: 'Footer Buttons',
                    properties: { flexDirection: 'row', justifyContent: 'flex-end', alignItems: 'center', flexWrap: 'nowrap', alignContent: 'stretch', gap: 15 },
                    items: [{ flex: '0 0 auto' }, { flex: '0 0 auto' }, { flex: '0 0 auto' }]
                },
                {
                    name: 'Equal Columns',
                    properties: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'stretch', flexWrap: 'nowrap', alignContent: 'stretch', gap: 20 },
                    items: [{ flex: '1' }, { flex: '1' }, { flex: '1' }, { flex: '1' }]
                }
            ];

            // Initialize
            updateFlexItems();
            updatePreview();
            generatePresets();

            // Property button clicks
            $('.property-btn').click(function() {
                const property = $(this).data('property');
                const value = $(this).data('value');
                
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                
                flexProperties[property] = value;
                updatePreview();
            });

            // Gap input
            $('#gapInput').on('input', function() {
                flexProperties.gap = parseInt($(this).val()) || 0;
                updatePreview();
            });

            // Add item
            $('#addItemBtn').click(function() {
                if (flexItems.length < 8) {
                    flexItems.push({ flex: '1', alignSelf: 'auto', order: 0 });
                    updateFlexItems();
                    updatePreview();
                }
            });

            // Remove item
            $(document).on('click', '.remove-item-btn', function() {
                const index = $(this).data('index');
                flexItems.splice(index, 1);
                updateFlexItems();
                updatePreview();
            });

            // Item property changes
            $(document).on('input', '.item-property', function() {
                const index = $(this).data('index');
                const property = $(this).data('property');
                const value = $(this).val();
                
                flexItems[index][property] = value;
                updatePreview();
            });

            // Copy code
            $('.code-output').click(function() {
                const code = $(this).text();
                copyToClipboard(code);
                showNotification();
            });

            function updateFlexItems() {
                const container = $('#flexItems');
                container.empty();

                flexItems.forEach((item, index) => {
                    const itemControl = $(`
                        <div class="item-controls">
                            <h4>Item ${index + 1}</h4>
                            <div>
                                <label>Flex: </label>
                                <input type="text" class="item-property item-input" data-index="${index}" data-property="flex" value="${item.flex || '1'}" placeholder="1">
                                
                                <label>Align Self: </label>
                                <select class="item-property item-input" data-index="${index}" data-property="alignSelf" style="width: 120px;">
                                    <option value="auto" ${item.alignSelf === 'auto' ? 'selected' : ''}>auto</option>
                                    <option value="flex-start" ${item.alignSelf === 'flex-start' ? 'selected' : ''}>flex-start</option>
                                    <option value="center" ${item.alignSelf === 'center' ? 'selected' : ''}>center</option>
                                    <option value="flex-end" ${item.alignSelf === 'flex-end' ? 'selected' : ''}>flex-end</option>
                                    <option value="stretch" ${item.alignSelf === 'stretch' ? 'selected' : ''}>stretch</option>
                                    <option value="baseline" ${item.alignSelf === 'baseline' ? 'selected' : ''}>baseline</option>
                                </select>
                                
                                <label>Order: </label>
                                <input type="number" class="item-property item-input" data-index="${index}" data-property="order" value="${item.order || 0}" style="width: 60px;">
                            </div>
                            ${flexItems.length > 1 ? `<button class="remove-item-btn" data-index="${index}">Remove Item</button>` : ''}
                        </div>
                    `);
                    container.append(itemControl);
                });
            }


            function updatePreview() {
                const preview = $('#flexPreview');
                
                // Apply container styles
                preview.css({
                    'display': 'flex',
                    'flex-direction': flexProperties.flexDirection,
                    'justify-content': flexProperties.justifyContent,
                    'align-items': flexProperties.alignItems,
                    'flex-wrap': flexProperties.flexWrap,
                    'align-content': flexProperties.alignContent,
                    'gap': `${flexProperties.gap}px`
                });

                // Clear and rebuild items
                preview.empty();
                flexItems.forEach((item, index) => {
                    const flexItem = $(`<div class="flex-item">${index + 1}</div>`);
                    flexItem.css({
                        'flex': item.flex || '1',
                        'align-self': item.alignSelf || 'auto',
                        'order': item.order || 0
                    });
                    preview.append(flexItem);
                });

                // Update code outputs
                updateCodeOutputs();
            }

            function updateCodeOutputs() {
                // Generate HTML
                let html = '<div class="flex-container">\n';
                flexItems.forEach((item, index) => {
                    html += `  <div class="flex-item">${index + 1}</div>\n`;
                });
                html += '</div>';

                // Generate CSS
                let css = '.flex-container {\n';
                css += '  display: flex;\n';
                css += `  flex-direction: ${flexProperties.flexDirection};\n`;
                css += `  justify-content: ${flexProperties.justifyContent};\n`;
                css += `  align-items: ${flexProperties.alignItems};\n`;
                css += `  flex-wrap: ${flexProperties.flexWrap};\n`;
                css += `  align-content: ${flexProperties.alignContent};\n`;
                css += `  gap: ${flexProperties.gap}px;\n`;
                css += '}\n\n';

                // Add item-specific styles
                flexItems.forEach((item, index) => {
                    const hasCustomStyles = item.flex !== '1' || item.alignSelf !== 'auto' || item.order !== 0;
                    if (hasCustomStyles) {
                        css += `.flex-item:nth-child(${index + 1}) {\n`;
                        if (item.flex !== '1') css += `  flex: ${item.flex};\n`;
                        if (item.alignSelf !== 'auto') css += `  align-self: ${item.alignSelf};\n`;
                        if (item.order !== 0) css += `  order: ${item.order};\n`;
                        css += '}\n\n';
                    }
                });

                $('#htmlOutput').text(html);
                $('#cssOutput').text(css);
            }

            function generatePresets() {
                const grid = $('#presetsGrid');
                presets.forEach((preset, index) => {
                    const presetPreview = $('<div class="preset-preview"></div>');
                    presetPreview.css({
                        'display': 'flex',
                        'flex-direction': preset.properties.flexDirection,
                        'justify-content': preset.properties.justifyContent,
                        'align-items': preset.properties.alignItems,
                        'flex-wrap': preset.properties.flexWrap,
                        'gap': `${preset.properties.gap}px`
                    });

                    preset.items.forEach((item, i) => {
                        const miniItem = $('<div class="mini-item"></div>');
                        miniItem.css('flex', item.flex || '1');
                        presetPreview.append(miniItem);
                    });

                    const presetItem = $(`
                        <div class="preset-item" data-preset="${index}">
                            <div class="preset-name">${preset.name}</div>
                        </div>
                    `);
                    presetItem.prepend(presetPreview);
                    presetItem.click(() => loadPreset(index));
                    grid.append(presetItem);
                });
            }

            function loadPreset(index) {
                const preset = presets[index];
                flexProperties = { ...preset.properties };
                flexItems = preset.items.map(item => ({
                    flex: item.flex || '1',
                    alignSelf: item.alignSelf || 'auto',
                    order: item.order || 0
                }));

                // Update UI
                $('.property-btn').removeClass('active');
                Object.keys(flexProperties).forEach(prop => {
                    if (prop !== 'gap') {
                        $(`.property-btn[data-property="${prop}"][data-value="${flexProperties[prop]}"]`).addClass('active');
                    }
                });
                
                $('#gapInput').val(flexProperties.gap);
                updateFlexItems();
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
        });
    </script>
</body>
</html>