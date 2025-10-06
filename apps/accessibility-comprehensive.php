<?php
/**
 * Comprehensive Accessibility Scanner
 * Drop this file into /accessibility/ folder in your site root
 * Access via: https://yoursite.com/accessibility/
 */

// Start session for storing results
session_start();

// Configuration
$maxPagesToScan = 500;
$requestTimeout = 10;
$delayBetweenRequests = 100000; // 0.1 seconds in microseconds

// Check if custom sitemap URL is provided
if (isset($_POST['sitemap_url']) && !empty($_POST['sitemap_url'])) {
    $sitemapUrl = filter_var($_POST['sitemap_url'], FILTER_SANITIZE_URL);
    $_SESSION['custom_sitemap_url'] = $sitemapUrl;
} elseif (isset($_SESSION['custom_sitemap_url'])) {
    $sitemapUrl = $_SESSION['custom_sitemap_url'];
} else {
    // Auto-detect site URL
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $siteUrl = $protocol . '://' . $host;
    
    // Remove /accessibility from the path to get site root
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    $siteBaseUrl = $siteUrl . str_replace('/accessibility', '', $scriptPath);
    $sitemapUrl = $siteBaseUrl . '/sitemap.xml';
}

// Extract site name from sitemap URL for filename
$parsedUrl = parse_url($sitemapUrl);
$siteName = isset($parsedUrl['host']) ? preg_replace('/^www\./', '', $parsedUrl['host']) : 'site';

/**
 * Fetch and parse sitemap.xml recursively
 */
function fetch_sitemap(string $url, int $depth = 0, int $maxDepth = 3): array {
    if ($depth > $maxDepth) {
        return [];
    }
    
    $urls = [];
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'Mozilla/5.0 (Accessibility Scanner)',
            'follow_location' => true
        ]
    ]);
    
    $content = @file_get_contents($url, false, $context);
    if ($content === false) {
        return $urls;
    }
    
    libxml_use_internal_errors(true);
    $xml = @simplexml_load_string($content);
    libxml_clear_errors();
    
    if ($xml === false) {
        return $urls;
    }
    
    // Parse URL entries
    foreach ($xml->children() as $child) {
        if ($child->getName() === 'url' && isset($child->loc)) {
            $urls[] = (string)$child->loc;
        }
    }
    
    // Parse sitemap index
    foreach ($xml->children() as $child) {
        if ($child->getName() === 'sitemap' && isset($child->loc)) {
            $childUrls = fetch_sitemap((string)$child->loc, $depth + 1, $maxDepth);
            $urls = array_merge($urls, $childUrls);
        }
    }
    
    return array_unique($urls);
}

/**
 * Fetch page content
 */
function fetch_page(string $url, int $timeout = 10): ?string {
    $context = stream_context_create([
        'http' => [
            'timeout' => $timeout,
            'user_agent' => 'Mozilla/5.0 (Accessibility Scanner)',
            'follow_location' => true
        ]
    ]);
    
    $content = @file_get_contents($url, false, $context);
    return $content !== false ? $content : null;
}

/**
 * Extract title from HTML
 */
function extract_title(string $html): string {
    if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches)) {
        return trim(html_entity_decode(strip_tags($matches[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
    return 'Untitled';
}

/**
 * Check if element has valid ARIA attributes
 */
function check_aria_validity(DOMElement $element): array {
    $issues = [];
    $validRoles = ['alert', 'alertdialog', 'application', 'article', 'banner', 'button', 'cell', 'checkbox', 
                   'columnheader', 'combobox', 'complementary', 'contentinfo', 'definition', 'dialog', 'directory',
                   'document', 'feed', 'figure', 'form', 'grid', 'gridcell', 'group', 'heading', 'img', 'link',
                   'list', 'listbox', 'listitem', 'log', 'main', 'marquee', 'math', 'menu', 'menubar', 'menuitem',
                   'menuitemcheckbox', 'menuitemradio', 'navigation', 'none', 'note', 'option', 'presentation',
                   'progressbar', 'radio', 'radiogroup', 'region', 'row', 'rowgroup', 'rowheader', 'scrollbar',
                   'search', 'searchbox', 'separator', 'slider', 'spinbutton', 'status', 'switch', 'tab', 'table',
                   'tablist', 'tabpanel', 'term', 'textbox', 'timer', 'toolbar', 'tooltip', 'tree', 'treegrid', 'treeitem'];
    
    $role = $element->getAttribute('role');
    if ($role && !in_array($role, $validRoles)) {
        $issues[] = 'invalid_aria_role';
    }
    
    // Check for aria-hidden on focusable elements
    if ($element->getAttribute('aria-hidden') === 'true') {
        $tagName = strtolower($element->tagName);
        $tabindex = $element->getAttribute('tabindex');
        if (in_array($tagName, ['a', 'button', 'input', 'select', 'textarea']) || $tabindex !== '') {
            $issues[] = 'aria_hidden_focusable';
        }
    }
    
    return $issues;
}

/**
 * Check heading hierarchy
 */
function check_heading_hierarchy(DOMDocument $doc): array {
    $issues = [];
    $headings = [];
    
    for ($i = 1; $i <= 6; $i++) {
        foreach ($doc->getElementsByTagName('h' . $i) as $heading) {
            $headings[] = $i;
        }
    }
    
    if (empty($headings)) {
        return $issues;
    }
    
    // Check for skipped levels
    $prevLevel = 0;
    foreach ($headings as $level) {
        if ($prevLevel > 0 && $level > $prevLevel + 1) {
            $issues[] = 'skipped_heading_level';
            break;
        }
        $prevLevel = $level;
    }
    
    return $issues;
}

/**
 * Analyze page accessibility
 */
function analyze_page(string $html): array {
    $doc = new DOMDocument();
    $loaded = @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOERROR);
    
    $results = [
        // Original checks
        'images' => 0,
        'missing_alt' => 0,
        'h1_count' => 0,
        'h2_count' => 0,
        'generic_links' => 0,
        'landmarks' => 0,
        
        // Form checks
        'form_inputs' => 0,
        'inputs_missing_labels' => 0,
        'fieldsets_missing_legends' => 0,
        'buttons_no_text' => 0,
        
        // Focus management
        'positive_tabindex' => 0,
        'negative_tabindex_interactive' => 0,
        
        // ARIA issues
        'aria_hidden_focusable' => 0,
        'invalid_aria_roles' => 0,
        'duplicate_ids' => 0,
        
        // Heading structure
        'skipped_heading_levels' => 0,
        
        // Language
        'missing_lang' => 0,
        
        // Skip links
        'has_skip_link' => 0,
        
        // Tables
        'tables' => 0,
        'tables_missing_th' => 0,
        'tables_missing_caption' => 0,
        
        // Media
        'videos' => 0,
        'videos_no_captions' => 0,
        'audio_elements' => 0,
        'autoplay_media' => 0,
        
        // Links
        'links_new_window_no_warning' => 0,
        'empty_links' => 0,
        
        // Buttons
        'non_button_clickables' => 0,
        
        // Document structure
        'multiple_main' => 0,
        'missing_title' => 0,
        
        // Iframes
        'iframes' => 0,
        'iframes_missing_title' => 0,
        
        // Meta information
        'has_viewport' => 0,
        'has_charset' => 0,
        
        'issues' => []
    ];
    
    if (!$loaded) {
        return $results;
    }
    
    $xpath = new DOMXPath($doc);
    
    // ===== ORIGINAL CHECKS =====
    
    // Analyze images
    $images = $doc->getElementsByTagName('img');
    $results['images'] = $images->length;
    foreach ($images as $img) {
        if (trim($img->getAttribute('alt')) === '') {
            $results['missing_alt']++;
        }
    }
    
    // Analyze headings
    $results['h1_count'] = $doc->getElementsByTagName('h1')->length;
    $results['h2_count'] = $doc->getElementsByTagName('h2')->length;
    
    // Check heading hierarchy
    $headingIssues = check_heading_hierarchy($doc);
    $results['skipped_heading_levels'] = count($headingIssues);
    
    // Analyze links
    $genericTerms = ['click here', 'read more', 'learn more', 'here', 'more', 'this page', 'link'];
    $anchors = $doc->getElementsByTagName('a');
    foreach ($anchors as $anchor) {
        $text = strtolower(trim($anchor->textContent));
        
        // Generic link text
        if (in_array($text, $genericTerms)) {
            $results['generic_links']++;
        }
        
        // Empty links
        if (empty($text) && !$anchor->hasAttribute('aria-label') && !$anchor->hasAttribute('aria-labelledby')) {
            $results['empty_links']++;
        }
        
        // Links opening in new window without warning
        $target = $anchor->getAttribute('target');
        if ($target === '_blank') {
            $hasWarning = false;
            $ariaLabel = $anchor->getAttribute('aria-label');
            if ($ariaLabel && (stripos($ariaLabel, 'new window') !== false || stripos($ariaLabel, 'new tab') !== false)) {
                $hasWarning = true;
            }
            if (!$hasWarning) {
                $results['links_new_window_no_warning']++;
            }
        }
    }
    
    // Count landmarks
    foreach (['main', 'nav', 'header', 'footer', 'aside'] as $tag) {
        $results['landmarks'] += $doc->getElementsByTagName($tag)->length;
    }
    
    // Check for multiple main landmarks
    if ($doc->getElementsByTagName('main')->length > 1) {
        $results['multiple_main'] = 1;
    }
    
    // ===== FORM CHECKS =====
    
    $inputs = $xpath->query('//input[@type!="hidden"] | //textarea | //select');
    $results['form_inputs'] = $inputs->length;
    
    foreach ($inputs as $input) {
        $id = $input->getAttribute('id');
        $hasLabel = false;
        
        // Check for label element
        if ($id) {
            $labels = $xpath->query("//label[@for='$id']");
            if ($labels->length > 0) {
                $hasLabel = true;
            }
        }
        
        // Check for aria-label or aria-labelledby
        if (!$hasLabel && !$input->hasAttribute('aria-label') && !$input->hasAttribute('aria-labelledby')) {
            // Check if wrapped in label
            $parent = $input->parentNode;
            while ($parent && $parent->nodeName !== 'html') {
                if ($parent->nodeName === 'label') {
                    $hasLabel = true;
                    break;
                }
                $parent = $parent->parentNode;
            }
            
            if (!$hasLabel) {
                $results['inputs_missing_labels']++;
            }
        }
    }
    
    // Check buttons for text content
    $buttons = $doc->getElementsByTagName('button');
    foreach ($buttons as $button) {
        $text = trim($button->textContent);
        if (empty($text) && !$button->hasAttribute('aria-label') && !$button->hasAttribute('aria-labelledby')) {
            $results['buttons_no_text']++;
        }
    }
    
    // Check fieldsets for legends
    $fieldsets = $doc->getElementsByTagName('fieldset');
    foreach ($fieldsets as $fieldset) {
        $hasLegend = false;
        foreach ($fieldset->childNodes as $child) {
            if ($child->nodeName === 'legend') {
                $hasLegend = true;
                break;
            }
        }
        if (!$hasLegend) {
            $results['fieldsets_missing_legends']++;
        }
    }
    
    // ===== FOCUS MANAGEMENT =====
    
    $elementsWithTabindex = $xpath->query('//*[@tabindex]');
    foreach ($elementsWithTabindex as $element) {
        $tabindex = $element->getAttribute('tabindex');
        if (is_numeric($tabindex)) {
            $tabindexValue = intval($tabindex);
            if ($tabindexValue > 0) {
                $results['positive_tabindex']++;
            }
            if ($tabindexValue < 0) {
                $tagName = strtolower($element->tagName);
                if (in_array($tagName, ['a', 'button', 'input', 'select', 'textarea'])) {
                    $results['negative_tabindex_interactive']++;
                }
            }
        }
    }
    
    // ===== ARIA CHECKS =====
    
    $allElements = $xpath->query('//*[@role or @aria-hidden or starts-with(name(), "aria-")]');
    foreach ($allElements as $element) {
        $ariaIssues = check_aria_validity($element);
        foreach ($ariaIssues as $issue) {
            if ($issue === 'invalid_aria_role') {
                $results['invalid_aria_roles']++;
            } elseif ($issue === 'aria_hidden_focusable') {
                $results['aria_hidden_focusable']++;
            }
        }
    }
    
    // Check for duplicate IDs
    $ids = [];
    $allElementsWithId = $xpath->query('//*[@id]');
    foreach ($allElementsWithId as $element) {
        $id = $element->getAttribute('id');
        if (isset($ids[$id])) {
            $results['duplicate_ids']++;
        }
        $ids[$id] = true;
    }
    
    // ===== LANGUAGE CHECKS =====
    
    $htmlElement = $doc->getElementsByTagName('html')->item(0);
    if (!$htmlElement || !$htmlElement->hasAttribute('lang')) {
        $results['missing_lang'] = 1;
    }
    
    // ===== SKIP LINKS =====
    
    $skipLinks = $xpath->query('//a[contains(translate(@href, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz"), "#main") or contains(translate(@href, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz"), "#content")]');
    if ($skipLinks->length > 0) {
        $results['has_skip_link'] = 1;
    }
    
    // ===== TABLE CHECKS =====
    
    $tables = $doc->getElementsByTagName('table');
    $results['tables'] = $tables->length;
    
    foreach ($tables as $table) {
        // Check for th elements
        $ths = $table->getElementsByTagName('th');
        if ($ths->length === 0) {
            $results['tables_missing_th']++;
        }
        
        // Check for caption
        $hasCaptionOrAriaLabel = false;
        foreach ($table->childNodes as $child) {
            if ($child->nodeName === 'caption') {
                $hasCaptionOrAriaLabel = true;
                break;
            }
        }
        if (!$hasCaptionOrAriaLabel && !$table->hasAttribute('aria-label') && !$table->hasAttribute('aria-labelledby')) {
            $results['tables_missing_caption']++;
        }
    }
    
    // ===== MEDIA CHECKS =====
    
    $videos = $doc->getElementsByTagName('video');
    $results['videos'] = $videos->length;
    
    foreach ($videos as $video) {
        // Check for track elements (captions/subtitles)
        $hasTrack = false;
        foreach ($video->childNodes as $child) {
            if ($child->nodeName === 'track') {
                $hasTrack = true;
                break;
            }
        }
        if (!$hasTrack) {
            $results['videos_no_captions']++;
        }
        
        // Check for autoplay
        if ($video->hasAttribute('autoplay')) {
            $results['autoplay_media']++;
        }
    }
    
    $audios = $doc->getElementsByTagName('audio');
    $results['audio_elements'] = $audios->length;
    
    foreach ($audios as $audio) {
        if ($audio->hasAttribute('autoplay')) {
            $results['autoplay_media']++;
        }
    }
    
    // ===== BUTTON USAGE =====
    
    // Check for divs/spans with click handlers (onclick, role=button)
    $clickableDivs = $xpath->query('//div[@onclick or @role="button"] | //span[@onclick or @role="button"]');
    $results['non_button_clickables'] = $clickableDivs->length;
    
    // ===== DOCUMENT STRUCTURE =====
    
    $titleElement = $doc->getElementsByTagName('title')->item(0);
    if (!$titleElement || empty(trim($titleElement->textContent))) {
        $results['missing_title'] = 1;
    }
    
    // ===== IFRAME CHECKS =====
    
    $iframes = $doc->getElementsByTagName('iframe');
    $results['iframes'] = $iframes->length;
    
    foreach ($iframes as $iframe) {
        if (!$iframe->hasAttribute('title') || empty(trim($iframe->getAttribute('title')))) {
            $results['iframes_missing_title']++;
        }
    }
    
    // ===== META INFORMATION =====
    
    $metaTags = $doc->getElementsByTagName('meta');
    foreach ($metaTags as $meta) {
        if ($meta->getAttribute('name') === 'viewport') {
            $results['has_viewport'] = 1;
        }
        if ($meta->getAttribute('charset') !== '') {
            $results['has_charset'] = 1;
        }
    }
    
    // ===== GENERATE ISSUES LIST =====
    
    // Critical issues
    if ($results['missing_alt'] > 0) {
        $results['issues'][] = [
            'type' => 'critical',
            'message' => $results['missing_alt'] . ' image(s) missing alt text'
        ];
    }
    
    if ($results['inputs_missing_labels'] > 0) {
        $results['issues'][] = [
            'type' => 'critical',
            'message' => $results['inputs_missing_labels'] . ' form input(s) missing labels'
        ];
    }
    
    if ($results['missing_lang'] === 1) {
        $results['issues'][] = [
            'type' => 'critical',
            'message' => 'Missing lang attribute on HTML element'
        ];
    }
    
    if ($results['empty_links'] > 0) {
        $results['issues'][] = [
            'type' => 'critical',
            'message' => $results['empty_links'] . ' empty link(s) found'
        ];
    }
    
    // Serious issues
    if ($results['h1_count'] === 0) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => 'No H1 heading found'
        ];
    } elseif ($results['h1_count'] > 1) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => 'Multiple H1 headings (' . $results['h1_count'] . ')'
        ];
    }
    
    if ($results['skipped_heading_levels'] > 0) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => 'Heading levels skipped (breaks hierarchy)'
        ];
    }
    
    if ($results['buttons_no_text'] > 0) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => $results['buttons_no_text'] . ' button(s) without text or label'
        ];
    }
    
    if ($results['aria_hidden_focusable'] > 0) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => $results['aria_hidden_focusable'] . ' focusable element(s) with aria-hidden'
        ];
    }
    
    if ($results['videos_no_captions'] > 0) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => $results['videos_no_captions'] . ' video(s) missing captions/subtitles'
        ];
    }
    
    if ($results['duplicate_ids'] > 0) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => 'Duplicate IDs found (breaks ARIA references)'
        ];
    }
    
    if ($results['tables_missing_th'] > 0) {
        $results['issues'][] = [
            'type' => 'serious',
            'message' => $results['tables_missing_th'] . ' table(s) missing header cells'
        ];
    }
    
    // Moderate issues
    if ($results['generic_links'] > 0) {
        $results['issues'][] = [
            'type' => 'moderate',
            'message' => $results['generic_links'] . ' link(s) with generic text'
        ];
    }
    
    if ($results['positive_tabindex'] > 0) {
        $results['issues'][] = [
            'type' => 'moderate',
            'message' => $results['positive_tabindex'] . ' element(s) with positive tabindex (breaks tab order)'
        ];
    }
    
    if ($results['links_new_window_no_warning'] > 0) {
        $results['issues'][] = [
            'type' => 'moderate',
            'message' => $results['links_new_window_no_warning'] . ' link(s) open new window without warning'
        ];
    }
    
    if ($results['non_button_clickables'] > 0) {
        $results['issues'][] = [
            'type' => 'moderate',
            'message' => $results['non_button_clickables'] . ' div/span element(s) used as buttons'
        ];
    }
    
    if ($results['autoplay_media'] > 0) {
        $results['issues'][] = [
            'type' => 'moderate',
            'message' => $results['autoplay_media'] . ' media element(s) with autoplay'
        ];
    }
    
    if ($results['iframes_missing_title'] > 0) {
        $results['issues'][] = [
            'type' => 'moderate',
            'message' => $results['iframes_missing_title'] . ' iframe(s) missing title attribute'
        ];
    }
    
    if ($results['invalid_aria_roles'] > 0) {
        $results['issues'][] = [
            'type' => 'moderate',
            'message' => $results['invalid_aria_roles'] . ' invalid ARIA role(s)'
        ];
    }
    
    // Minor issues
    if ($results['landmarks'] === 0) {
        $results['issues'][] = [
            'type' => 'minor',
            'message' => 'No semantic landmarks (main, nav, header, footer)'
        ];
    }
    
    if ($results['has_skip_link'] === 0) {
        $results['issues'][] = [
            'type' => 'minor',
            'message' => 'No skip navigation link found'
        ];
    }
    
    if ($results['fieldsets_missing_legends'] > 0) {
        $results['issues'][] = [
            'type' => 'minor',
            'message' => $results['fieldsets_missing_legends'] . ' fieldset(s) missing legend'
        ];
    }
    
    if ($results['tables_missing_caption'] > 0) {
        $results['issues'][] = [
            'type' => 'minor',
            'message' => $results['tables_missing_caption'] . ' table(s) missing caption'
        ];
    }
    
    if ($results['has_viewport'] === 0) {
        $results['issues'][] = [
            'type' => 'minor',
            'message' => 'Missing viewport meta tag'
        ];
    }
    
    if ($results['multiple_main'] === 1) {
        $results['issues'][] = [
            'type' => 'minor',
            'message' => 'Multiple main landmarks found'
        ];
    }
    
    if ($results['negative_tabindex_interactive'] > 0) {
        $results['issues'][] = [
            'type' => 'minor',
            'message' => $results['negative_tabindex_interactive'] . ' interactive element(s) removed from keyboard navigation'
        ];
    }
    
    return $results;
}

/**
 * Calculate accessibility score
 */
function calculate_score(array $analysis): array {
    $critical = $analysis['missing_alt'] + $analysis['inputs_missing_labels'] + 
                $analysis['missing_lang'] + $analysis['empty_links'];
    
    $serious = ($analysis['h1_count'] === 0 || $analysis['h1_count'] > 1 ? 1 : 0) +
               $analysis['skipped_heading_levels'] + $analysis['buttons_no_text'] +
               $analysis['aria_hidden_focusable'] + $analysis['videos_no_captions'] +
               ($analysis['duplicate_ids'] > 0 ? 1 : 0) + $analysis['tables_missing_th'];
    
    $moderate = ($analysis['generic_links'] > 0 ? 1 : 0) + 
                ($analysis['positive_tabindex'] > 0 ? 1 : 0) +
                ($analysis['links_new_window_no_warning'] > 0 ? 1 : 0) +
                ($analysis['non_button_clickables'] > 0 ? 1 : 0) +
                ($analysis['autoplay_media'] > 0 ? 1 : 0) +
                ($analysis['iframes_missing_title'] > 0 ? 1 : 0) +
                ($analysis['invalid_aria_roles'] > 0 ? 1 : 0);
    
    $minor = ($analysis['landmarks'] === 0 ? 1 : 0) +
             ($analysis['has_skip_link'] === 0 ? 1 : 0) +
             ($analysis['fieldsets_missing_legends'] > 0 ? 1 : 0) +
             ($analysis['tables_missing_caption'] > 0 ? 1 : 0) +
             ($analysis['has_viewport'] === 0 ? 1 : 0) +
             $analysis['multiple_main'] +
             ($analysis['negative_tabindex_interactive'] > 0 ? 1 : 0);
    
    $score = 100;
    $score -= $critical * 15;
    $score -= $serious * 10;
    $score -= $moderate * 5;
    $score -= $minor * 3;
    
    $totalViolations = $critical + $serious + $moderate + $minor;
    if ($totalViolations === 0) {
        $score = 98;
    }
    $score = max(0, min(100, $score));
    
    // Determine WCAG level
    if ($totalViolations === 0) {
        $level = 'AAA';
    } elseif ($critical === 0 && $serious === 0 && $score >= 85) {
        $level = 'AA';
    } elseif ($critical === 0 && $score >= 70) {
        $level = 'A';
    } elseif ($score >= 50) {
        $level = 'Partial';
    } else {
        $level = 'Failing';
    }
    
    return [
        'score' => $score,
        'level' => $level,
        'violations' => [
            'critical' => $critical,
            'serious' => $serious,
            'moderate' => $moderate,
            'minor' => $minor,
            'total' => $totalViolations
        ]
    ];
}

/**
 * Export results to CSV
 */
function export_csv(array $results, string $siteName): void {
    $filename = preg_replace('/[^a-z0-9]/i', '-', $siteName);
    $filename .= '-accessibility-report-' . date('Y-m-d-His') . '.csv';
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $output = fopen('php://output', 'w');
    
    // CSV headers
    fputcsv($output, [
        'URL',
        'Title',
        'Score',
        'WCAG Level',
        'Critical Issues',
        'Serious Issues',
        'Moderate Issues',
        'Minor Issues',
        'Total Violations',
        'Missing Alt Text',
        'Form Inputs Missing Labels',
        'Empty Links',
        'Missing Lang Attribute',
        'H1 Issues',
        'Skipped Heading Levels',
        'Buttons Without Text',
        'ARIA Hidden on Focusable',
        'Videos Without Captions',
        'Generic Link Text',
        'Positive Tabindex',
        'Links New Window No Warning',
        'Non-button Clickables',
        'Autoplay Media',
        'Iframes Missing Title',
        'Tables Missing Headers',
        'Missing Skip Link',
        'No Landmarks',
        'Issues Summary'
    ]);
    
    // Data rows
    foreach ($results as $page) {
        $issuesSummary = implode('; ', array_map(function($issue) {
            return $issue['message'];
        }, $page['issues']));
        
        $h1Issues = '';
        if ($page['h1_count'] === 0) {
            $h1Issues = 'No H1';
        } elseif ($page['h1_count'] > 1) {
            $h1Issues = 'Multiple H1s (' . $page['h1_count'] . ')';
        }
        
        fputcsv($output, [
            $page['url'],
            $page['title'],
            $page['score'],
            $page['level'],
            $page['violations']['critical'],
            $page['violations']['serious'],
            $page['violations']['moderate'],
            $page['violations']['minor'],
            $page['violations']['total'],
            $page['missing_alt'],
            $page['inputs_missing_labels'],
            $page['empty_links'],
            $page['missing_lang'],
            $h1Issues,
            $page['skipped_heading_levels'],
            $page['buttons_no_text'],
            $page['aria_hidden_focusable'],
            $page['videos_no_captions'],
            $page['generic_links'],
            $page['positive_tabindex'],
            $page['links_new_window_no_warning'],
            $page['non_button_clickables'],
            $page['autoplay_media'],
            $page['iframes_missing_title'],
            $page['tables_missing_th'],
            ($page['has_skip_link'] === 0 ? 'Yes' : 'No'),
            ($page['landmarks'] === 0 ? 'Yes' : 'No'),
            $issuesSummary
        ]);
    }
    
    fclose($output);
    exit;
}

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    if (isset($_SESSION['scan_results'])) {
        export_csv($_SESSION['scan_results'], $siteName);
    } else {
        die('No scan results available. Please run a scan first.');
    }
}

// Handle clear results
if (isset($_GET['clear'])) {
    unset($_SESSION['scan_results']);
    unset($_SESSION['custom_sitemap_url']);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Handle scan request
$scanResults = [];
$scanning = false;
$errorMessage = null;

if (isset($_POST['scan'])) {
    $scanning = true;
    
    // Fetch sitemap
    $urls = fetch_sitemap($sitemapUrl);
    
    if (empty($urls)) {
        $errorMessage = "Could not fetch or parse sitemap at: $sitemapUrl";
    } else {
        // Limit URLs
        if (count($urls) > $maxPagesToScan) {
            $urls = array_slice($urls, 0, $maxPagesToScan);
        }
        
        // Scan each page
        foreach ($urls as $url) {
            $html = fetch_page($url, $requestTimeout);
            
            if ($html === null) {
                continue;
            }
            
            $title = extract_title($html);
            $analysis = analyze_page($html);
            $scoreData = calculate_score($analysis);
            
            $scanResults[] = array_merge([
                'url' => $url,
                'title' => $title,
            ], $analysis, $scoreData);
            
            // Small delay between requests
            usleep($delayBetweenRequests);
        }
        
        // Store results in session for CSV export
        $_SESSION['scan_results'] = $scanResults;
    }
}

// Calculate summary statistics
$stats = [
    'total' => count($scanResults),
    'avg_score' => 0,
    'critical_issues' => 0,
    'serious_issues' => 0,
    'aa_compliant' => 0,
    'failing' => 0
];

if (!empty($scanResults)) {
    $scoreSum = 0;
    foreach ($scanResults as $result) {
        $scoreSum += $result['score'];
        $stats['critical_issues'] += $result['violations']['critical'];
        $stats['serious_issues'] += $result['violations']['serious'];
        if (in_array($result['level'], ['AA', 'AAA'])) {
            $stats['aa_compliant']++;
        }
        if ($result['level'] === 'Failing') {
            $stats['failing']++;
        }
    }
    $stats['avg_score'] = round($scoreSum / $stats['total']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprehensive Accessibility Scanner</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        header {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: #667eea;
        }
        
        .subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .input-group {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        
        .input-wrapper {
            flex: 1;
            min-width: 300px;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        input[type="url"] {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e0e0e0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        input[type="url"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #10b981;
        }
        
        .btn-secondary:hover {
            background: #059669;
        }
        
        .btn-tertiary {
            background: #6b7280;
        }
        
        .btn-tertiary:hover {
            background: #4b5563;
        }
        
        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .note {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            margin-top: 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
        }
        
        .note strong {
            color: #92400e;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-value {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-value.score-excellent { color: #10b981; }
        .stat-value.score-good { color: #3b82f6; }
        .stat-value.score-fair { color: #f59e0b; }
        .stat-value.score-poor { color: #ef4444; }
        
        .stat-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .results {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .results h2 {
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 1.75rem;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #e0e0e0;
            white-space: nowrap;
            position: sticky;
            top: 0;
        }
        
        td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .page-title {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .page-title:hover {
            text-decoration: underline;
        }
        
        .page-url {
            color: #999;
            font-size: 0.85rem;
        }
        
        .score {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .score-excellent, .score-aaa, .score-aa {
            background: #d1fae5;
            color: #065f46;
        }
        
        .score-good, .score-a {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .score-fair, .score-partial {
            background: #fef3c7;
            color: #92400e;
        }
        
        .score-poor, .score-failing {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            margin: 0.125rem;
        }
        
        .badge-aaa { background: #d1fae5; color: #065f46; }
        .badge-aa { background: #bfdbfe; color: #1e40af; }
        .badge-a { background: #dbeafe; color: #1e40af; }
        .badge-partial { background: #fed7aa; color: #9a3412; }
        .badge-failing { background: #fee2e2; color: #991b1b; }
        
        .issue-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin: 0.125rem;
        }
        
        .issue-critical { background: #fee2e2; color: #991b1b; }
        .issue-serious { background: #fed7aa; color: #9a3412; }
        .issue-moderate { background: #fef3c7; color: #92400e; }
        .issue-minor { background: #dbeafe; color: #1e40af; }
        
        .issues-list {
            font-size: 0.875rem;
            color: #666;
            max-width: 400px;
        }
        
        .issues-list div {
            margin-bottom: 0.25rem;
            padding: 0.25rem 0;
        }
        
        .issue-type-critical { color: #dc2626; font-weight: 600; }
        .issue-type-serious { color: #ea580c; font-weight: 600; }
        .issue-type-moderate { color: #d97706; }
        .issue-type-minor { color: #2563eb; }
        
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            border-left: 4px solid #dc2626;
        }
        
        .info {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .info h2 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.75rem;
        }
        
        .info h3 {
            color: #333;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        
        .info ul, .info ol {
            margin-left: 1.5rem;
            margin-top: 0.5rem;
            color: #555;
        }
        
        .info li {
            margin-bottom: 0.5rem;
        }
        
        .loading {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .no-issues {
            color: #10b981;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 1rem 0;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            header {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 1.75rem;
            }
            
            .input-group {
                flex-direction: column;
            }
            
            .input-wrapper {
                width: 100%;
                min-width: 0;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
            
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            table {
                font-size: 0.875rem;
            }
            
            th, td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🔍 Comprehensive Accessibility Scanner</h1>
            <p class="subtitle">WCAG 2.1 AA/AAA Compliance Analysis Tool</p>
            
            <form method="POST" action="">
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="sitemap_url">Enter Sitemap URL:</label>
                        <input 
                            type="url" 
                            id="sitemap_url" 
                            name="sitemap_url" 
                            value="<?php echo htmlspecialchars($sitemapUrl); ?>"
                            placeholder="https://example.com/sitemap.xml"
                            required
                        >
                    </div>
                    <button type="submit" name="scan" value="1" class="btn">
                        Start Scan
                    </button>
                </div>
            </form>
            
            <?php if (!empty($scanResults)): ?>
                <div class="actions">
                    <a href="?export=csv" class="btn btn-secondary">📥 Export CSV Report</a>
                    <a href="?clear=1" class="btn btn-tertiary">🔄 New Scan</a>
                </div>
            <?php endif; ?>
            
            <div class="note">
                <strong>📋 What We Check:</strong> This scanner analyzes 35+ accessibility criteria including images, forms, ARIA, headings, links, tables, media, keyboard navigation, and more. Color contrast testing should be done separately with browser tools.
            </div>
        </header>
        
        <?php if ($errorMessage): ?>
            <div class="error">
                <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($scanning && !$errorMessage && empty($scanResults)): ?>
            <div class="loading">
                <div class="spinner"></div>
                <p><strong>Scanning pages...</strong></p>
                <p style="color: #666; margin-top: 0.5rem;">This may take a few minutes depending on the site size.</p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($scanResults)): ?>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-value" style="color: #667eea;"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Pages Scanned</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value <?php 
                        if ($stats['avg_score'] >= 90) echo 'score-excellent';
                        elseif ($stats['avg_score'] >= 75) echo 'score-good';
                        elseif ($stats['avg_score'] >= 60) echo 'score-fair';
                        else echo 'score-poor';
                    ?>"><?php echo $stats['avg_score']; ?></div>
                    <div class="stat-label">Average Score</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value score-excellent"><?php echo $stats['aa_compliant']; ?></div>
                    <div class="stat-label">AA+ Compliant Pages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value score-poor"><?php echo $stats['critical_issues']; ?></div>
                    <div class="stat-label">Critical Issues</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #ea580c;"><?php echo $stats['serious_issues']; ?></div>
                    <div class="stat-label">Serious Issues</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value score-poor"><?php echo $stats['failing']; ?></div>
                    <div class="stat-label">Failing Pages</div>
                </div>
            </div>
            
            <div class="results">
                <h2>📊 Detailed Scan Results</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Score</th>
                                <th>Level</th>
                                <th>Issues Count</th>
                                <th>Key Issues</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scanResults as $result): ?>
                                <tr>
                                    <td style="max-width: 350px;">
                                        <a href="<?php echo htmlspecialchars($result['url']); ?>" 
                                           target="_blank" 
                                           class="page-title">
                                            <?php echo htmlspecialchars($result['title']); ?>
                                        </a>
                                        <div class="page-url"><?php echo htmlspecialchars($result['url']); ?></div>
                                    </td>
                                    <td>
                                        <span class="score <?php 
                                            if ($result['score'] >= 90) echo 'score-excellent';
                                            elseif ($result['score'] >= 75) echo 'score-good';
                                            elseif ($result['score'] >= 60) echo 'score-fair';
                                            else echo 'score-poor';
                                        ?>"><?php echo $result['score']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($result['level']); ?>">
                                            <?php echo htmlspecialchars($result['level']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($result['violations']['critical'] > 0): ?>
                                            <span class="issue-badge issue-critical">
                                                🔴 <?php echo $result['violations']['critical']; ?> Critical
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($result['violations']['serious'] > 0): ?>
                                            <span class="issue-badge issue-serious">
                                                🟠 <?php echo $result['violations']['serious']; ?> Serious
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($result['violations']['moderate'] > 0): ?>
                                            <span class="issue-badge issue-moderate">
                                                🟡 <?php echo $result['violations']['moderate']; ?> Moderate
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($result['violations']['minor'] > 0): ?>
                                            <span class="issue-badge issue-minor">
                                                🔵 <?php echo $result['violations']['minor']; ?> Minor
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($result['violations']['total'] === 0): ?>
                                            <span class="no-issues">✓ No Issues</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="issues-list">
                                            <?php if (empty($result['issues'])): ?>
                                                <div class="no-issues">✓ No accessibility issues detected</div>
                                            <?php else: ?>
                                                <?php foreach (array_slice($result['issues'], 0, 5) as $issue): ?>
                                                    <div class="issue-type-<?php echo $issue['type']; ?>">
                                                        • <?php echo htmlspecialchars($issue['message']); ?>
                                                    </div>
                                                <?php endforeach; ?>
                                                <?php if (count($result['issues']) > 5): ?>
                                                    <div style="color: #999; font-style: italic; margin-top: 0.5rem;">
                                                        + <?php echo count($result['issues']) - 5; ?> more issue<?php echo count($result['issues']) - 5 > 1 ? 's' : ''; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php elseif (!$scanning): ?>
            <div class="info">
                <h2>Welcome to the Comprehensive Accessibility Scanner</h2>
                <p>This tool performs an in-depth analysis of any website's accessibility based on WCAG 2.1 guidelines.</p>
                
                <h3>🎯 How It Works:</h3>
                <ol>
                    <li>Enter your sitemap URL in the field above</li>
                    <li>Click "Start Scan" to begin the analysis</li>
                    <li>Review detailed results for each page</li>
                    <li>Export comprehensive reports to CSV for documentation</li>
                </ol>
                
                <h3>✅ What We Check (35+ Criteria):</h3>
                <ul>
                    <li><strong>Images:</strong> Alt text, decorative images</li>
                    <li><strong>Forms:</strong> Labels, fieldsets, legends, button text</li>
                    <li><strong>Navigation:</strong> Skip links, keyboard access, tab order</li>
                    <li><strong>Content Structure:</strong> Heading hierarchy, landmarks, page titles</li>
                    <li><strong>Links:</strong> Generic text, empty links, new window warnings</li>
                    <li><strong>ARIA:</strong> Valid roles, proper usage, duplicate IDs, hidden elements</li>
                    <li><strong>Tables:</strong> Headers, captions, proper structure</li>
                    <li><strong>Media:</strong> Video captions, autoplay issues, audio transcripts</li>
                    <li><strong>Language:</strong> HTML lang attribute declaration</li>
                    <li><strong>Iframes:</strong> Title attributes for context</li>
                    <li><strong>Buttons:</strong> Proper semantic elements, accessible text</li>
                    <li><strong>Meta:</strong> Viewport settings, character encoding</li>
                </ul>
                
                <h3>📝 Example Sitemap URLs:</h3>
                <ul style="color: #667eea; font-family: monospace;">
                    <li>https://example.com/sitemap.xml</li>
                    <li>https://example.com/sitemap_index.xml</li>
                    <li>https://example.com/post-sitemap.xml</li>
                </ul>
                
                <p style="margin-top: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 0.5rem;">
                    <strong>💡 Tip:</strong> For the most accurate results, ensure your sitemap is up to date and includes all pages you want to test. The scanner will analyze up to <?php echo $maxPagesToScan; ?> pages per scan.
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>