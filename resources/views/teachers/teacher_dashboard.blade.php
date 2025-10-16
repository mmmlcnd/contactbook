@extends('layouts.dashboard')

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