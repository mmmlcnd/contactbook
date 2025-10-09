<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Entry;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class StudentController extends Controller
{

    public function dashboard()
    {

        // ログイン中のユーザー情報を取得
        $student = Auth::user(); // Illuminate\Support\Facades\Auth を使う

        return view('students.dashboard', compact('student'));
    }

    // 連絡帳入力画面
    public function create()
    {
        // 仮にログイン中の生徒を取得（PoC用）
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return "生徒データが見つかりません。";
        }

        return view('students.create_entry', compact('student'));
    }

    // 入力内容を保存
    public function store(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date',
            'content' => 'required|string',
        ]);

        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return "生徒データが見つかりません。";
        }

        Entry::create([
            'student_id' => $student->id,
            'entry_date' => $request->entry_date,
            'content' => $request->content,
            'is_read' => false,
        ]);

        return redirect()->route('students.entries.past')->with('success', '提出しました');
    }

    // 過去記録閲覧
    public function past()
    {
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return "生徒データが見つかりません。";
        }

        $entries = $student->entries()->orderBy('entry_date', 'desc')->get();

        return view('students.past_entries', compact('entries'));
    }
}
