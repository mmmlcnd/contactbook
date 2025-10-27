<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;

//デフォルト画面
Route::get('/', function () {
    return view('index');
})->name('home');

// --------------------
// ログイン画面
// --------------------

Route::get('/login/admin', [LoginController::class, 'adminLoginForm'])->name('login.admin');
// URLで/login/adminが指定された場合、POSTされたデータを下記のコントローラーのadminLogin関数に飛ばしている
Route::post('/login/admin', [LoginController::class, 'adminLogin']);

Route::get('/login/teacher', [LoginController::class, 'teacherLoginForm'])->name('login.teacher');
Route::post('/login/teacher', [LoginController::class, 'teacherLogin']);

Route::get('/login/student', [LoginController::class, 'studentLoginForm'])->name('login.student');
Route::post('/login/student', [LoginController::class, 'studentLogin']);

// ログアウト
Route::post('/logout/{role}', [LoginController::class, 'logout'])->name('logout');

// --------------------
// 生徒用画面
// --------------------

Route::prefix('students')->group(function () {
    // ダッシュボード
    Route::get('dashboard', [StudentController::class, 'dashboard'])->name('students.dashboard');
    // 連絡帳入力画面
    Route::resource('entries', StudentController::class)->only([
        'create',
        'store'
    ])->names([
        'create' => 'students.entries.create',
        'store' => 'students.entries.store'
    ]);
    // 連絡帳履歴画面
    Route::get('entries/past', [StudentController::class, 'showPastEntries'])->name('students.entries.past');
    // 連絡帳詳細画面
    Route::get('entries/{id}', [StudentController::class, 'showEntryDetail'])->name('students.entries.detail')
        ->where('id', '[0-9]+');
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
    //管理者ユーザー作成画面
    // Route::resource 以下2つのルート生成
    // 1. GET /admins/users/create -> AdminController@create (フォーム表示)
    // 2. POST /admins/users       -> AdminController@store (登録処理)
    Route::resource('users', AdminController::class)->only([
        'create',
        'store'
    ])->names([
        'create' => 'admins.create',
        'store' => 'admins.store'
    ]);
});
