<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserAttendanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//管理者（ログイン前）
Route::get('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login'); //管理者ログイン
//管理者（ログイン後）
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/attendance/list', [AdminController::class, 'attendance'])->name('admin.attendance');
});

//一般ユーザー（ログイン前）
Route::get('/login', [UserController::class, 'userLogin'])->name('login'); //一般ユーザーログイン表示
Route::post('/login', [UserController::class, 'postLogin'])->name('postLogin'); //一般ユーザーログイン処理
Route::get('/register', [UserController::class, 'getRegister'])->name('getRegister'); //会員登録表示
Route::post('/register', [UserController::class, 'postRegister'])->name('postRegister'); //会員登録処理
//一般ユーザー（ログイン後）
Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [UserAttendanceController::class, 'getStamping'])->name('getStamping'); //出勤登録画面表示
    Route::post('/attendance/clock-in', [UserAttendanceController::class, 'clockIn'])->name('user.attendance.clockIn'); //出勤開始処理
    Route::post('/attendance/clock-out', [UserAttendanceController::class, 'clockOut'])->name('user.attendance.clockOut'); //出勤終了処理
    Route::post('/attendance/start-break', [UserAttendanceController::class, 'startBreak'])->name('user.attendance.startBreak'); //休憩開始処理
    Route::post('/attendance/end-break', [UserAttendanceController::class, 'endBreak'])->name('user.attendance.endBreak'); //休憩終了処理
    Route::get('/attendance/list', [UserAttendanceController::class, 'attendanceList'])->name('user.attendance.list'); //勤怠一覧表示
    Route::get('/attendance/{id}', [UserAttendanceController::class, 'getDetail'])->name('user.attendance.detail'); //勤怠詳細画面表示
    Route::post('/attendance/{id}/edit-request', [UserAttendanceController::class, 'postEditRequest'])->name('user.attendance.editRequest'); //修正申請処理
    Route::get('/stamp_correction_request/list', [UserAttendanceController::class, 'getEditRequestList'])->name('user.attendance.postRequestList'); //申請一覧表示
    //Route::post('/logout', [UserController::class, 'logout'])->name('logout'); //ログアウト
});
