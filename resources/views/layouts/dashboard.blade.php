<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>連絡帳管理システム</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- ヘッダー --}}
    <header class="bg-blue-600 text-white shadow">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center p-4">
            <h1 class="text-xl font-semibold mb-2 md:mb-0">連絡帳管理システム</h1>

            {{-- ロールに応じたナビゲーションを表示するロジック --}}
            @php
            // コントローラーから渡される変数 $userType を使用
            $role = $userType ?? ($_SESSION['user_type'] ?? null);
            @endphp

            <nav class="flex space-x-4">
                @if ($role)
                <a href="/" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center bg-indigo-700 font-bold">
                    <i class="fas fa-school mr-1"></i> トップページ
                </a>
                @endif
                @if ($role == 'admin')
                {{-- 管理者向けナビゲーション --}}
                <a href="/admins/dashboard" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-chart-line mr-1"></i> ダッシュボード
                </a>
                <a href="/admins/create" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-users-cog mr-1"></i> ユーザー管理
                </a>
                <!-- <a href="/admins/classes/manage" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-chalkboard mr-1"></i> クラス管理
                </a> -->
                @elseif ($role == 'teacher')
                {{-- 教師向けナビゲーション --}}
                <a href="/teachers/dashboard" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-home mr-1"></i> ダッシュボード
                </a>
                <a href="/teachers/status" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center font-bold bg-indigo-700">
                    <i class="fas fa-bell mr-1"></i> 提出確認
                </a>
                @elseif ($role == 'student')
                {{-- 生徒向けナビゲーション --}}
                <a href="/students/dashboard" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-home mr-1"></i> ダッシュボード
                </a>
                <a href="/students/entries/create" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-pencil-alt mr-1"></i> 連絡帳提出
                </a>
                <a href="/students/past/entries" class="hover:bg-indigo-700 p-2 rounded transition duration-150 flex items-center">
                    <i class="fas fa-history mr-1"></i> 提出履歴
                </a>
                @endif

                {{-- ログアウトは全ユーザー共通で表示 --}}
                @if ($role)
                <a href="/logout" class="bg-red-500 hover:bg-red-600 p-2 rounded transition duration-150 flex items-center font-bold">
                    <i class="fas fa-sign-out-alt mr-1"></i> ログアウト
                </a>
                @endif
            </nav>
        </div>
    </header>

    {{-- メインコンテンツ --}}
    <main class="container mx-auto flex-1 p-6 mt-6">
        @yield('content')
    </main>

    {{-- フッター --}}
    <footer class="bg-gray-700 text-white text-center py-3 text-sm mt-auto">
        &copy; 2025 架空私立中学校 連絡帳システム
    </footer>

</body>

</html>