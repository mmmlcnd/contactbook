<?php
// セッションからメッセージを取得
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ $title }}</h1>

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

        <form method="POST" action="/admins/users/create" class="space-y-6">
            @csrf

            {{-- ユーザータイプ選択 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label for="user_type" class="block text-sm font-medium text-gray-700">ユーザー種別</label>
                    <select id="user_type" name="user_type" required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm"
                        onchange="toggleFields()">
                        <option value="student" <?php echo $userType === 'student' ? 'selected' : ''; ?>>生徒</option>
                        <option value="teacher" <?php echo $userType === 'teacher' ? 'selected' : ''; ?>>教師</option>
                        <option value="admin" <?php echo $userType === 'admin' ? 'selected' : ''; ?>>管理者</option>
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

            {{-- 生徒・教師共通フィールド --}}
            <div id="name_field" style="<?php echo $userType === 'admin' ? 'display: none;' : 'display: block;'; ?>">
                <label for="name" class="block text-sm font-medium text-gray-700">氏名</label>
                <input type="text" id="name" name="name"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div id="grade_field" style="<?php echo $userType === 'admin' ? 'display: none;' : 'display: block;'; ?>">
                <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">学年</label>
                <input type="number" id="grade" name="grade" min="1" max="6"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="例: 3">
            </div>

            <div id="class_id_field" style="<?php echo $userType === 'admin' ? 'display: none;' : 'display: block;'; ?>">
                <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">クラス</label>
                <input type="number" id="class_id" name="class_id" min="1"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="例: 1 (1年A組などに対応)">
                <p class="mt-1 text-xs text-gray-500">
                    ※ クラス管理機能で登録したIDを入力してください。
                </p>
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

    /**
     * ユーザータイプ（生徒/教師/管理者）に応じて、フォームフィールドの表示・非表示を切り替えます。
     */
    function toggleFields() {
        const userType = document.getElementById('user_type').value;

        const nameField = document.getElementById('name_field');
        const nameInput = document.getElementById('name');

        const gradeField = document.getElementById('grade_field');
        const gradeInput = document.getElementById('grade');

        const classIdField = document.getElementById('class_id_field');
        const classIdInput = document.getElementById('class_id');

        // デフォルトで全ての必須属性をリセット
        nameInput.removeAttribute('required');
        classIdInput.removeAttribute('required');

        if (userType === 'student' || userType === 'teacher') {
            // 生徒の場合: grade, class_id を表示し、必須とする
            nameField.style.display = 'block';
            nameInput.setAttribute('required', 'required');
            gradeField.style.display = 'block';
            gradeInput.setAttribute('required', 'required');
            classIdField.style.display = 'block';
            classIdInput.setAttribute('required', 'required');
        } else {
            // admin の場合: 非表示にし、必須属性を削除
            nameField.style.display = 'none';
            nameInput.removeAttribute('required');
            gradeField.style.display = 'none';
            gradeInput.removeAttribute('required');
            classIdField.style.display = 'none';
            classIdInput.removeAttribute('required');
        }
    }
</script>
@endsection