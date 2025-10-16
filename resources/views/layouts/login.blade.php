<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン | 連絡帳システム</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">
            @yield('title')
        </h2>

        {{-- AuthControllerから渡された $error 変数を Blade 構文で表示する --}}
        @if (isset($error) && $error)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm font-medium">
            {{ $error }}
        </div>
        @endif

        {{-- フォーム --}}
        @yield('form')

        <footer>
            <a href="{{ route('home') }}" class="block text-center text-sm text-indigo-600 hover:text-indigo-500 mt-4 hover:underline transition duration-150 ease-in-out">
                最初の画面に戻る
            </a>
        </footer>
    </div>
</body>

</html>