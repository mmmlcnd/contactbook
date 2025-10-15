<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ... データベース接続コードや、その他の require_once が続く ...

// 1. データベース接続を確立し、$pdo 変数をグローバルに定義する
// index.phpの場所（public）から親ディレクトリ（..）へ移動し、database/db_connect.php を読み込みます。
require_once __DIR__ . '/../database/db_connect.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
