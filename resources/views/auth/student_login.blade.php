<!-- @extends('layouts.login')

@section('content')
<h2>生徒ログイン</h2>

@if($errors->any())
<div class="error-message">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('login.student') }}">
    @csrf
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">ログイン</button>
</form>
@endsection -->