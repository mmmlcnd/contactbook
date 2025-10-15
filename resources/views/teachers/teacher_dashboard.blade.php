@extends('layouts.dashboard')

@section('nav')
<nav class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
    <a href="{{ route('teachers.status') }}" class="hover:underline">担当クラスの提出状況確認</a>
    <a href="{{ route('teachers.status') }}" class="hover:underline">過去の記録確認</a>

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
    <h2>教師ダッシュボード</h2>

    <?php /*
    <!-- <p>ようこそ、{{ auth()->guard('student')->user()->name}}さん</p> -->*/ ?>

    <ul>
        <li><a href="{{ route('teachers.status') }}">担当クラスの提出状況確認</a></li>
        <li><a href="{{ route('teachers.status') }}">過去の記録確認</a></li>
        <?php /* <!-- <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </li> --> */ ?>
    </ul>


</div>
@endsection