<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>連絡帳システム</title>
    {{-- LaravelのCSSなどを読み込み --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- ヘッダー部分 --}}
    <header class="bg-blue-600 text-white p-4 shadow">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-semibold">連絡帳管理システム</h1>
            <nav>
                <a href="{{ url('/entries/create') }}" class="mr-4 hover:underline">連絡帳提出</a>
                <a href="{{ url('/entries/status') }}" class="hover:underline">提出状況</a>
            </nav>
        </div>
    </header>

    {{-- メインコンテンツ --}}
    <main class="container mx-auto flex-1 p-6">
        @yield('content') {{-- 各ページの中身がここに入る --}}
    </main>

    {{-- フッター --}}
    <footer class="bg-gray-700 text-white text-center py-3 text-sm">
        &copy; 2025 架空私立中学校 連絡帳システム
    </footer>

</body>

</html>
