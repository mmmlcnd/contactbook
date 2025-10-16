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

//管理者ダッシュボード画面
// Route::get('/admins/dashboard', function () {
//     return view('admins/admin_dashboard');
// });
Route::get('/admins/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

//管理者ユーザー作成画面
Route::get('/admins/create', [AdminController::class, 'showUserManagement'])->name('create');
Route::post('/admins/create', [AdminController::class, 'createUser']);

// 管理者クラス管理画面
Route::get('/admins/classes', [AdminController::class, 'manageClasses'])->name('classes');

// --------------------
// ログイン画面
// --------------------
Route::get('/login/student', [AuthController::class, 'studentLoginForm'])->name('login.student');
Route::post('/login/student', [AuthController::class, 'studentLogin']);

Route::get('/login/teacher', [AuthController::class, 'teacherLoginForm'])->name('login.teacher');
Route::post('/login/teacher', [AuthController::class, 'teacherLogin']);

Route::get('/login/admin', [AuthController::class, 'adminLoginForm'])->name('login.admin');
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
    Route::get('entries/create', [StudentController::class, 'create'])->name('students.entries.create');
    // 入力内容を保存するPOSTルート
    Route::post('entries', [StudentController::class, 'store'])->name('students.entries.store');
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

    // 生徒・教師アカウント管理画面
    Route::get('users/create', [AdminUserController::class, 'showCreateUserForm'])->name('users.create');

    // ユーザー作成処理
    Route::post('users/store', [AdminUserController::class, 'storeNewUser'])->name('users.store');

    // クラス情報管理画面）
    Route::get('classes', [AdminController::class, 'manageClasses'])->name('classes');

    // 管理者用提出状況一覧画面（全生徒）
    Route::get('/entries/status', [EntryController::class, 'status'])->name('entries.status');
});
