@extends('layouts.dashboard')

@section('nav')
<nav class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
    <a href="{{ route('create') }}" class="hover:underline">ユーザー管理</a>
    <a href="{{ route('classes') }}" class="hover:underline">クラス管理</a>
    <a href="{{ route('entries.status') }}" class="hover:underline">提出状況一覧</a>

    <?php /* <form method="POST" action="{{ route('logout', ['role' => $user->role]) }}" class="inline">
        @csrf
        <button type="submit" class="ml-0 md:ml-4 px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-white">
            ログアウト
        </button>
    </form>

    @else
    <a href="{{ route('login.student') }}" class="hover:underline">生徒ログイン</a>
    <a href="{{ route('login.teacher') }}" class="hover:underline">教師ログイン</a>
    <a href="{{ route('login.admin') }}" class="hover:underline">管理者ログイン</a>
    @endif */ ?>
</nav>
@endsection

@section('content')
<div class="dashboard-container">
    <h2>管理者ダッシュボード</h2>

    <?php //<p>ようこそ、{{ auth()->guard('admin')->user()->name }} さん</p> -->
    ?>

    <p>ようこそ、さん</p>

    <ul>
        <li><a href="{{ route('create') }}">生徒・教師アカウント管理</a></li>
        <li><a href="{{ route('classes') }}">クラス管理</a></li>

        <?php /*
        //
        <li><a href="{{ route('entries.status') }}">全生徒の提出状況確認</a></li>

        <!-- <li>
            <form action="{{ route('logout', ['role' => 'admin']) }}" method="POST">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </li> --> */ ?>
    </ul>
</div>
@endsection