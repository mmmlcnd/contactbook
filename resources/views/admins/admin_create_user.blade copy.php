@php
// セッションからメッセージを取得
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$selectedUserType = 'student';
@endphp

@extends('layouts.dashboard')

@section('title', 'ユーザー作成')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">ユーザー作成</h1>

    {{-- メッセージ表示エリア --}}
    @if ($success)
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ $success }}</span>
    </div>
    @endif
    @if ($error)
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ $error }}</span>
    </div>
    @endif

    {{-- ユーザー作成フォームエリア --}}
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4 border-b pb-2 text-gray-700">新規ユーザー作成</h2>

        <form method="POST" action="{{ route('admins.create') }}" class="space-y-6">
            @csrf

            {{-- ユーザータイプ選択 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label for="user_type" class="block text-sm font-medium text-gray-700">ユーザー種別</label>
                    <select id="user_type" name="user_type" required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm"
                        onchange="toggleFields()">
                        <option value="student" @selected($selectedUserType==='student' )>生徒</option>
                        <option value="teacher" @selected($selectedUserType==='teacher' )>教師</option>
                        <option value="admin" @selected($selectedUserType==='admin' )>管理者</option>
                    </select>
                </div>
            </div>

            {{-- 共通フィールド (Email, Password) --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
                <input type="email" id="email" name="email" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">パスワード</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- 氏名フィールド (管理者も表示、全ユーザーで必須) --}}
            <div id="name_field">
                <label for="name" class="block text-sm font-medium text-gray-700">氏名</label>
                <input type="text" id="name" name="name"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- フリガナ（kana）フィールド (管理者も表示、全ユーザーで必須) --}}
            <div id="kana_field">
                <label for="kana" class="block text-sm font-medium text-gray-700">フリガナ (カナ)</label>
                <input type="text" id="kana" name="kana"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="例: ヤマダタロウ">
            </div>

            {{-- クラスIDフィールド (生徒・教師のみ必須、管理者非表示) --}}
            <div id="class_id_field" style="
                @if ($selectedUserType === 'admin') display: none;
                @else display: block;
                @endif">
                <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">所属クラス (学年/クラス)</label>
                <select id="class_id" name="class_id"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                    <option value="" disabled selected>学年・クラスを選択してください</option>

                    {{-- $classes 変数 (id, name, grade を持つ) はコントローラから渡されることを想定 --}}
                    @if (!empty($classes))
                    @foreach ($classes as $class)
                    <option value="{{ $class->id }}">
                        {{ $class->grade }}年 {{ $class->name }}
                    </option>
                    @endforeach
                    @else
                    <option value="" disabled>クラス情報がありません</option>
                    @endif
                </select>
            </div>

            <div>
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    ユーザーを登録する
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 初期状態でユーザータイプに応じてフォームフィールドを切り替え
        toggleFields();
    });

    function toggleFields() {
        const selectedUserType = document.getElementById('user_type').value;

        const nameField = document.getElementById('name_field');
        const nameInput = document.getElementById('name');

        const kanaField = document.getElementById('kana_field');
        const kanaInput = document.getElementById('kana');

        const classIdField = document.getElementById('class_id_field');
        const classIdInput = document.getElementById('class_id');

        // デフォルトで全ての必須属性をリセット
        nameInput.removeAttribute('required');
        kanaInput.removeAttribute('required'); // kanaもリセット
        classIdInput.removeAttribute('required');
        // nameFieldとkanaFieldは常に表示されている

        if (selectedUserType === 'student' || selectedUserType === 'teacher') {
            // 生徒・教師の場合: 氏名、フリガナ、学年、クラスを必須とする
            nameInput.setAttribute('required', 'required');
            kanaInput.setAttribute('required', 'required');
            classIdInput.setAttribute('required', 'required');

            // 学年とクラスIDを表示
            classIdField.style.display = 'block';

        } else {
            // admin の場合: 氏名、フリガナを必須とする
            nameInput.setAttribute('required', 'required');
            kanaInput.setAttribute('required', 'required');

            // 学年、クラスIDは非表示にする
            classIdField.style.display = 'none';
        }
    }
</script>
@endsection
