<?php
$file = $_SERVER['DOCUMENT_ROOT'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (empty(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) || parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/') {
    require __DIR__ . '/../index.php';
} elseif (file_exists($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    require $file;
} else {
    return false;
}