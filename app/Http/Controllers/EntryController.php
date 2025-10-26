<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\StudentTeacher;

class EntryController extends Controller
{
    // 連絡帳入力画面を表示
    public function create()
    {
        // 前登校日を自動計算（今日の1日前）
        $defaultDate = now()->subDay()->toDateString();
        return view('entries.create', compact('defaultDate'));
    }

    // フォーム送信処理（DBに保存）
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'entry_date' => 'required|date',
            'content' => 'required|string'
        ]);

        // DBに保存
        Entry::create([
            'student_id' => $request->student_id,
            // 現在ログイン機能を外している
            // 'student_id' => Auth::user()->id, // ログイン中の生徒ID
            'entry_date' => $request->entry_date,
            'content' => $request->content,
            'is_read' => false  // 担任未確認
        ]);

        // 送信後に同じ画面へリダイレクト + 成功メッセージ
        return redirect()->route('entries.create')->with('success', '提出完了');
    }

    // 提出状況を取得し、一覧画面に渡す処理
    // public function status()
    // {
    //     // 担当クラスの生徒とその提出状況を取得
    //     // PoCなので全生徒を取得
    //     $students = StudentTeacher::with('user', 'class', 'entries')->get();

    //     return view('entries.status', compact('students'));
    // }
}
