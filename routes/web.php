<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;

//デフォルト画面
Route::get('/', function () {
    return view('index');
})->name('home');

// --------------------
// ログイン画面
// --------------------
Route::get('/login/student', [AuthController::class, 'studentLoginForm'])->name('login.student');
Route::post('/login/student', [AuthController::class, 'Login("students")']);

Route::get('/login/teacher', [AuthController::class, 'teacherLoginForm'])->name('login.teacher');
Route::post('/login/teacher', [AuthController::class, 'teacherLogin']);

Route::get('/login/admin', [AuthController::class, 'adminLoginForm'])->name('login.admin');
// URLで/login/adminが指定された場合、POSTされたデータを下記のコントローラーのadminLogin関数に飛ばしている
Route::post('/login/admin', [AuthController::class, 'adminLogin']);

// ログアウト
Route::post('/logout/{role}', [AuthController::class, 'logout'])->name('logout');

// --------------------
// 生徒用画面
// --------------------
Route::prefix('students')->group(function () {
    // 生徒用ダッシュボード
    Route::get('dashboard', [StudentController::class, 'dashboard'])->name('students.dashboard');
    // 生徒用連絡帳入力画面
    Route::get('entries/create', [StudentController::class, 'showCreateEntryForm'])->name('students.entries.create');
    // 入力内容を保存するPOSTルート
    Route::post('entries/create', [StudentController::class, 'createEntry']);
    // 連絡帳履歴画面
    Route::get('entries/past', [StudentController::class, 'past'])->name('students.entries.past');
});

// --------------------
// 教師用画面
// --------------------
Route::prefix('teachers')->group(function () {
    // 教師用ダッシュボード
    Route::get('dashboard', [TeacherController::class, 'dashboard'])->name('teachers.dashboard');
    // 提出状況一覧画面（担当クラス）
    Route::get('status', [TeacherController::class, 'showSubmissionList'])->name('teachers.status');
    // 既読処理画面用 (GET /teachers/read/{id})
    Route::get('read/{id}', [TeacherController::class, 'readEntry'])
        ->where('id', '[0-9]+');
    // スタンプ押印処理 (POST /teachers/stamp/{id})
    Route::post('stamp/{id}', [TeacherController::class, 'stampEntry'])
        ->where('id', '[0-9]+');
    // 過去提出記録一覧画面（担当クラス）
    Route::get('entries/past', [TeacherController::class, 'past'])->name('teachers.entries.past');
});

// --------------------
// 管理者用画面
// --------------------
Route::prefix('admins')->group(function () {
    // 管理者用ダッシュボード
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admins.dashboard');
    // Route::get('/admins/dashboard', function () {
    //     return view('admins/admin_dashboard');
    // });

    //管理者ユーザー作成画面
    Route::get('create', [AdminController::class, 'showUserManagement'])->name('admins.create');
    Route::post('create', [AdminController::class, 'createUser']);
    // 管理者クラス管理画面
    Route::get('classes', [AdminController::class, 'manageClasses'])->name('classes');
});
