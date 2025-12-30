<?php
// router.php for local development

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Serve existing files directly (images, css, js, etc.)
if (file_exists(__DIR__ . $path) && !is_dir(__DIR__ . $path)) {
    return false; // Let PHP serve the file
}

// Handle root path
if ($path === '/' || $path === '/index') {
    require 'index.html';
    return;
}

// Append .html if file exists
$htmlFile = __DIR__ . $path . '.html';
if (file_exists($htmlFile)) {
    require $htmlFile;
    return;
}

// Append .php if file exists
$phpFile = __DIR__ . $path . '.php';
if (file_exists($phpFile)) {
    require $phpFile;
    return;
}

// 404
http_response_code(404);
echo "404 Not Found";
