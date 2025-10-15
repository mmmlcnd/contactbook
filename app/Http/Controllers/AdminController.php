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
    // ダッシュボード画面表示
    public function dashboard()
    {
        return view('admins.admin_dashboard');
    }

    // クラス管理画面表示
    public function createUser()
    {
        return view('admins.admin_create_user');
    }

    // クラス管理画面表示
    public function manageClasses()
    {
        return view('admins.admin_manage_classes');
    }

    // テスト
    // public function nameClass()
    // {
    //     $adminname = "s";
    //     return view('admins.admin_create_user', compact('adminname'));
    // }
}
