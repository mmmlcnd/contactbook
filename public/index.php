<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ... データベース接続コードや、その他の require_once が続く ...

// 1. データベース接続を確立し、$pdo 変数をグローバルに定義する
// index.phpの場所（public）から親ディレクトリ（..）へ移動し、database/db_connect.php を読み込みます。
require_once __DIR__ . '/../database/db_connect.php';

// ★ 新しい追加: AdminDashboardController と TeacherController を読み込む
// (Composerのオートロードに頼らず、明示的に読み込むことで確実に動作させる)
// require_once __DIR__ . '/../app/Http/Controllers/AdminDashboardController.php';
// require_once __DIR__ . '/../app/Http/Controllers/TeacherController.php';
// ★★★ Contoroller.php の読み込みも必須 ★★★
require_once __DIR__ . '/../app/Http/Controllers/Controller.php';

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
