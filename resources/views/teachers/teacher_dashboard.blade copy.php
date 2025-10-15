@extends('layouts.dashboard')

@section('content')
<div class="dashboard-container">
    <h2>教師ダッシュボード</h2>

    <?php /* <!-- <p>ようこそ、{{ auth()->guard('teacher')->user()->name }} 先生</p> -->

    <ul>
        <li><a href="{{ route('teachers.status') }}">担当クラスの提出状況確認</a></li>
        <li><a href="{{ route('teachers.entries.past') }}">過去の記録確認</a></li>
        <li>
            <form action="{{ route('logout', ['role' => 'teacher']) }}" method="POST">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </li>
    </ul>

    */ ?>
</div>
@endsection