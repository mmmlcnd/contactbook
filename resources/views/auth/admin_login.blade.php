@extends('layouts.login')

@section('title', '管理者ログイン')

@section('form')
<form method="POST" action="{{ route('login.admin') }}">
    @csrf
    <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium mb-2">メールアドレス</label>
        <input type="email" name="email" id="email" required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div class="mb-6">
        <label for="password" class="block text-gray-700 font-medium mb-2">パスワード</label>
        <input type="password" name="password" id="password" required
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <button type="submit" class="btn btn-blue">ログイン</button>
</form>
@endsection