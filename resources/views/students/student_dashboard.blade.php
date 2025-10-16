@extends('layouts.dashboard')

@section('content')
<div class="dashboard-container">
    <h2>生徒ダッシュボード</h2>

    <?php /*
    <!-- <p>ようこそ、{{ auth()->guard('student')->user()->name}}さん</p> -->*/ ?>

    <ul>
        <li><a href="{{ route('students.entries.create') }}">連絡帳を提出する</a></li>
        <li><a href="{{ route('students.entries.past') }}">過去の提出記録を見る</a></li>
        <?php /* <!-- <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </li> --> */ ?>
    </ul>


</div>
@endsection