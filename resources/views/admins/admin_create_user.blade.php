@extends('layouts.dashboard')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">ユーザー追加</h2>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admins.users.store') }}">
        @csrf

        {{-- 名前 --}}
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">名前</label>
            <input type="text" name="name" id="name" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- メール --}}
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium mb-2">メールアドレス</label>
            <input type="email" name="email" id="email" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- パスワード --}}
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium mb-2">パスワード</label>
            <input type="password" name="password" id="password" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- ユーザー種別 --}}
        <div class="mb-4">
            <label for="role" class="block text-gray-700 font-medium mb-2">ユーザー種別</label>
            <select name="role" id="role" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">選択してください</option>
                <option value="student">生徒</option>
                <option value="teacher">教師</option>
            </select>
        </div>

        {{-- 学年 --}}
        <div class="mb-4">
            <label for="grade" class="block text-gray-700 font-medium mb-2">学年</label>
            <select name="grade" id="grade"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">選択してください</option>
                <option value="1">1年</option>
                <option value="2">2年</option>
                <option value="3">3年</option>
            </select>
        </div>

        {{-- クラス --}}
        <div class="mb-6">
            <label for="class_id" class="block text-gray-700 font-medium mb-2">クラス</label>
            <select name="class_id" id="class_id"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">選択してください</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- 送信 --}}
        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition">
            ユーザー追加
        </button>
    </form>
</div>
@endsection