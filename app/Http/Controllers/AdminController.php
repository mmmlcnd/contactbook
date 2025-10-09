<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Entry;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admins.dashboard');
    }

    // ユーザー管理画面
    public function manageUsers()
    {
        $students = Student::with('user')->get();
        $teachers = Teacher::with('user')->get();
        return view('admins.manage_users', compact('students', 'teachers'));
    }

    // クラス管理画面
    public function manageClasses()
    {
        $classes = Classes::all();
        return view('admins.manage_classes', compact('classes'));
    }
}
