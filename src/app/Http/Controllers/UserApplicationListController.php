<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\AttendanceEdit;
use App\Models\BreakRecord;
use App\Models\User;
use Carbon\Carbon;

class UserApplicationListController extends Controller
{
    // 申請一覧画面表示
    public function getEditRequestList()
    {
        $pendingEdits = AttendanceEdit::with('user', 'attendance')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedEdits = AttendanceEdit::with('user', 'attendance')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.user_application_list', compact('pendingEdits', 'approvedEdits'));
    }
}
