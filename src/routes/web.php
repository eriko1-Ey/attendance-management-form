<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
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
    Route::get('/attendance', [UserController::class, 'getStamping'])->name('getStamping'); //出勤登録画面表示
    Route::post('/logout', [UserController::class, 'logout'])->name('logout'); //ログアウト
});
