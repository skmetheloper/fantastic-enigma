<?php

error_reporting(-1);
ini_set('display_errors', 'Off');

require __DIR__.'/../vendor/autoload.php';

define('HTTP_HEADER_FORMAT', '/^HTTP_(.+)$/');

$query = $_GET;
$data = $_POST;
$files = $_FILES;
$cookie = $_COOKIE;
$method = $_SERVER['REQUEST_METHOD'] ?: 'GET';
$uri = $_SERVER['REQUEST_URI'] ?: '/';
$path = parse_url($uri, PHP_URL_PATH);

$headers = [];

array_map(function ($name) use(&$headers) {
  if (!preg_match(HTTP_HEADER_FORMAT, $name, $matched)) return;
  $name = str_replace('_', '-', strtolower($matched[1]));
  $headers[$name] = $_SERVER[$name];
}, array_keys($_SERVER));

$res = compact('method', 'headers', 'uri', 'path', 'query', 'data', 'cookie', 'files');
$res = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

header('Content-Type: application/json;charset=utf-8');
header('Access-Control-Allow-Origin: *');

print($res);
