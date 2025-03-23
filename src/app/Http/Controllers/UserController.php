<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //一般ユーザーログイン画面表示
    public function userLogin()
    {
        return view('user.user_login');
    }

    //一般ユーザーログイン処理
    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role !== 'user') {
                Auth::logout();
                return back()->with('error', '一般ユーザーとしてログインしてください。');
            }
            return redirect()->route('getStamping');
        }

        return back()->with('error', 'ログインに失敗しました。');
    }

    //会員登録画面に遷移
    public function getRegister()
    {
        return view('user.user_register');
    }

    //会員登録処理
    public function postRegister(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user); // 自動ログイン

        return redirect()->route('getStamping'); // 出勤登録画面へ
    }

    //ログアウト
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
