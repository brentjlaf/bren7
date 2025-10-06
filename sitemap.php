<?php
// Set the content-type header to XML
header("Content-Type: application/xml; charset=utf-8");

$base_url = "https://www.bren7.com"; // Replace with your site's base URL
$directory = __DIR__; // Directory to scan, typically the root directory of your project

// Get all files in the directory and its subdirectories
function getPhpFiles($dir, $base_url) {
    $files = [];
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        
        // Only include .php files
        if ($file->getExtension() == 'php') {
            $filePath = str_replace($dir, '', $file->getPathname()); // Remove directory prefix
            $filePath = str_replace('\\', '/', $filePath); // Normalize slashes for URLs
            $url = $base_url . $filePath; // Create full URL for the file
            $files[] = $url;
        }
    }
    return $files;
}

// Generate list of URLs
$urls = getPhpFiles($directory, $base_url);

// Get the current date
$lastmod = date('Y-m-d');

// Start the XML structure
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// Loop through URLs and create entries
foreach ($urls as $url) {
    echo "    <url>\n";
    echo "        <loc>" . htmlspecialchars($url) . "</loc>\n";
    echo "        <lastmod>$lastmod</lastmod>\n";
    echo "        <changefreq>weekly</changefreq>\n";
    echo "        <priority>0.8</priority>\n";
    echo "    </url>\n";
}

// Close the XML structure
echo "</urlset>";
?>
