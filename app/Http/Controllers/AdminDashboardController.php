<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/Controller.php';

class AdminDashboardController extends Controller
{
    /**
     * 管理者ダッシュボードのメイン画面を表示します。
     */
    public function index()
    {
        // 認証はindex.phpのルーティングで完了しているため、すぐにコンテンツを表示
        return "
            <!DOCTYPE html>
            <html lang='ja'>
            <head>
                <meta charset='UTF-8'>
                <title>管理者ダッシュボード</title>
                <style>
                    body { font-family: sans-serif; text-align: center; margin-top: 50px; }
                    .success { color: green; font-size: 24px; }
                    .user-info { margin-top: 20px; border: 1px solid #ccc; padding: 15px; display: inline-block; }
                </style>
            </head>
            <body>
                <h1>✅ 認証成功！管理者ダッシュボード</h1>
                <p class='success'>Adminとしてログインしました。</p>
                <div class='user-info'>
                    <p>ユーザータイプ: " . htmlspecialchars($_SESSION['user_type'] ?? '未設定') . "</p>
                </div>
            </body>
            </html>
        ";
    }
}
