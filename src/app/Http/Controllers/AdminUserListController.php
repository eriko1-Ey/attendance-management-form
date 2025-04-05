<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;

class AdminUserListController extends Controller
{
    //スタッフ一覧表示
    public function getUserList()
    {
        // 一般ユーザー（role が 'user'）を取得
        $users = User::where('role', 'user')->get();

        // view に渡す
        return view('admin.admin_user_list', compact('users'));
    }
}
