<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //管理者ログイン
    public function getAdminLogin()
    {
        return view('admin.admin_login');
    }

    // 管理者ログイン処理
    public function postAdminLogin(AdminLoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('getAdminAttendanceRecord');
        }

        return back()->withErrors([
            'email' => '認証情報が正しくありません。',
        ]);
    }

    public function AdminLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('getAdminLogin');
    }
}
