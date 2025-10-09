<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン | 連絡帳システム</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
// 現在のURLパスまたはルート名からログインタイプを判定
$path = request()->path();
$role = 'student'; // デフォルト

if (Str::contains($path, 'teacher')) {
$role = 'teacher';
} elseif (Str::contains($path, 'admin')) {
$role = 'admin';
}
@endphp

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">
            @if(isset($role) && $role === 'teacher')
            教師ログイン
            @elseif(isset($role) && $role === 'admin')
            管理者ログイン
            @else
            生徒ログイン
            @endif
        </h2>

        {{-- エラーメッセージ --}}
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ログインフォーム --}}
        <form method="POST" action="@if(isset($role))
                        {{ route('login.' . $role) }}
                    @else
                        {{ route('login.student') }}
                    @endif">
            @csrf

            {{-- メールアドレス --}}
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">メールアドレス</label>
                <input type="email" name="email" id="email" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- パスワード --}}
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">パスワード</label>
                <input type="password" name="password" id="password" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- 送信ボタン --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">
                ログイン!!!
            </button>
        </form>
    </div>
</body>

</html>