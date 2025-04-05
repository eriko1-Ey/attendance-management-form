<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserAttendanceController;
use App\Http\Controllers\UserApplicationListController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminUserListController;
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
Route::get('/admin/login', [AdminController::class, 'getAdminLogin'])->name('getAdminLogin'); //管理者ログイン表示
Route::post('/admin/login', [AdminController::class, 'postAdminLogin'])->name('postAdminLogin'); //管理者ログイン処理
//管理者（ログイン後）
Route::middleware(['auth:admin', 'admin'])->group(function () {
    Route::get('/admin/attendance/list', [AdminAttendanceController::class, 'getAdminAttendanceRecord'])->name('getAdminAttendanceRecord'); //管理者用の勤怠一覧表示
    Route::get('/admin/staff/list', [AdminUserListController::class, 'getUserList'])->name('getUserList'); //スタッフ一覧表示
    Route::get('/admin/attendance/staff/{user_id}', [AdminAttendanceController::class, 'getUserAttendanceRecord'])->name('getUserAttendanceRecord'); //スタッフの月次勤怠表示
    Route::get('/admin/staff/{user_id}/attendance/csv', [AdminAttendanceController::class, 'exportCsv'])->name('admin.user.attendance.csv'); //csv出力
    Route::get('/admin/attendance/{id}/edit', [AdminAttendanceController::class, 'editAttendance'])->name('admin.attendance.edit'); //月次詳細画面表示
    Route::post('/admin/attendance/{id}/update', [AdminAttendanceController::class, 'updateAttendance'])->name('admin.attendance.update'); //詳細編集処理
    //Route::post('/admin/logout', [AdminController::class, 'AdminLogout'])->name('AdminLogout'); //ログアウト
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
    Route::get('/stamp_correction_request/list', [UserApplicationListController::class, 'getEditRequestList'])->name('user.attendance.postRequestList'); //申請一覧表示
    //Route::post('/logout', [UserController::class, 'logout'])->name('logout'); //ログアウト
});
