<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Entry;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::user(); // Illuminate\Support\Facades\Auth を使う
        return view('teachers.dashboard', compact('teacher'));
    }

    public function status()
    {
        // 仮のログインユーザーID（例: 3）
        $loggedInUserId = 3;

        // 仮ログイン中の教師を取得
        $teacher = Teacher::where('user_id', $loggedInUserId)->first();

        if (!$teacher) {
            return "教師データが見つかりません。";
        }

        // クラスや生徒情報も取得してビューに渡す
        $students = $teacher->class->students ?? [];

        return view('teachers.status', compact('teacher', 'students'));
    }

    public function readEntry($id)
    {
        $entry = Entry::findOrFail($id);
        $entry->is_read = true;
        $entry->save();
        return back()->with('success', '既読にしました');
    }

    public function past()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $entries = Entry::orderBy('entry_date', 'desc')->get();
        return view('teachers.past_entries', compact('entries'));
    }
}


// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Teacher;
// use App\Models\Student;
// use Illuminate\Support\Facades\Auth;

// class TeacherController extends Controller
// {
//     public function status()
//     {
//         // ログイン中の教師情報を取得
//         $teacher = Teacher::where('user_id', Auth::id())->first();

//         if (!$teacher) {
//             return "教師データが見つかりません。";
//         }

//         // 担当クラスの生徒とその提出状況を取得
//         $students = Student::with(['user', 'entries'])
//             ->where('class_id', $teacher->class_id)
//             ->get();

//         // Blade にデータを渡す
//         return view('teachers.status', compact('teacher', 'students'));
//     }
// }
