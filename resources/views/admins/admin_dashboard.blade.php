@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <h2>管理者ダッシュボード</h2>

    <!-- <p>ようこそ、{{ auth()->guard('admin')->user()->name }} さん</p>s -->

    <ul>
        <li><a href="{{ route('admins.users.create') }}">生徒・教師アカウント管理</a></li>
        <li><a href="{{ route('admins.classes') }}">クラス管理</a></li>
        <li><a href="{{ route('entries.status') }}">全生徒の提出状況確認</a></li>
        <!-- <li>
            <form action="{{ route('logout', ['role' => 'admin']) }}" method="POST">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </li> -->
    </ul>
</div>
@endsection