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
        return view('admins.admin_dashboard');
    }

    // AdminUserControllerに記載？
    // ユーザー管理画面
    // public function createUsers()
    // {
    //     $students = Student::with('user')->get();
    //     $teachers = Teacher::with('user')->get();
    //     return view('admins.admin_create_user', compact('students', 'teachers'));
    // }

    // クラス管理画面
    public function manageClasses()
    {
        $classes = Classes::all();
        return view('admins.manage_classes', compact('classes'));
    }

    public function nameClass()
    {
        $adminname = "s";
        return view('admins.admin_create_user', compact('adminname'));
    }
}
