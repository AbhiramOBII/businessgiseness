<?php

/**
 * cPanel shared hosting entry point.
 * Forwards all requests to Laravel's public/index.php
 * Use this when document root cannot be changed to /public
 */

$publicPath = __DIR__ . '/public';

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

require_once $publicPath . '/index.php';
