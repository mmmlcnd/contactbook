<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // ユーザー追加フォーム表示
    public function showCreateUserForm()
    {
        // クラス情報を取得（必要に応じてモデル名を変更）
        $classes = \App\Models\Classes::all();

        // Bladeに渡す
        return view('admins.admin_create_user', compact('classes'));
    }

    // ユーザーを保存する
    public function storeNewUser(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:student,teacher',
            'grade' => 'required|integer|min:1|max:3',
            'class' => 'required|string|max:5',
        ]);

        // ユーザー作成
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'grade' => $validated['grade'],
            'class' => $validated['class'],
        ]);

        return redirect()->route('admins.users.create')->with('success', 'ユーザーを追加しました！');
    }
}
