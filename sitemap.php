<?php
// Set the content-type header to XML
header('Content-Type: application/xml; charset=utf-8');

$base_url = 'https://www.bren7.com'; // Replace with your site's base URL
$directory = __DIR__; // Directory to scan, typically the root directory of your project

// Build a list of PHP files relative to the project directory.
function getPhpFiles(string $dir, string $base_url): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            $dir,
            FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS
        )
    );

    foreach ($iterator as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') {
            continue;
        }

        $relativePath = substr($file->getPathname(), strlen($dir));
        $relativePath = ltrim($relativePath, DIRECTORY_SEPARATOR);
        $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);

        $url = rtrim($base_url, '/') . '/' . $relativePath;
        $files[] = [
            'loc' => $url,
            'lastmod' => date('Y-m-d', $file->getMTime()),
        ];
    }

    usort($files, static function (array $a, array $b): int {
        return strcmp($a['loc'], $b['loc']);
    });

    return $files;
}

// Generate list of URLs
$urls = getPhpFiles($directory, $base_url);

// Start the XML structure
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// Loop through URLs and create entries
foreach ($urls as $urlInfo) {
    echo "    <url>\n";
    echo "        <loc>" . htmlspecialchars($urlInfo['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . "</loc>\n";
    echo "        <lastmod>" . $urlInfo['lastmod'] . "</lastmod>\n";
    echo "        <changefreq>weekly</changefreq>\n";
    echo "        <priority>0.8</priority>\n";
    echo "    </url>\n";
}

// Close the XML structure
echo "</urlset>";
?>
