<!-- <!DOCTYPE html>
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
            <nav class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
                @php
                $user = auth()->user();
                @endphp

                @if($user)
                @if($user->role === 'student')
                <a href="{{ route('students.entries.create') }}" class="hover:underline">連絡帳提出</a>
                <a href="{{ route('students.entries.past') }}" class="hover:underline">過去提出</a>
                @elseif($user->role === 'teacher')
                <a href="{{ route('teachers.status') }}" class="hover:underline">提出状況</a>
                <a href="{{ route('teachers.entries.past') }}" class="hover:underline">過去提出</a>
                @elseif($user->role === 'admin')
                <a href="{{ route('admins.users.create') }}" class="hover:underline">ユーザー管理</a>
                <a href="{{ route('admins.classes') }}" class="hover:underline">クラス管理</a>
                <a href="{{ route('entries.status') }}" class="hover:underline">提出状況一覧</a>
                @endif

                <form method="POST" action="{{ route('logout', ['role' => $user->role]) }}" class="inline">
                    @csrf
                    <button type="submit" class="ml-0 md:ml-4 px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-white">
                        ログアウト
                    </button>
                </form>

                @else
                <a href="{{ route('login.student') }}" class="hover:underline">生徒ログイン</a>
                <a href="{{ route('login.teacher') }}" class="hover:underline">教師ログイン</a>
                <a href="{{ route('login.admin') }}" class="hover:underline">管理者ログイン</a>
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

</html> -->