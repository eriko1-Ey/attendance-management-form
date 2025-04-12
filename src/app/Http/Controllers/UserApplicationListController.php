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
        $userId = auth()->id();

        $edits = AttendanceEdit::with('user', 'attendance')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingEdits = $edits->filter(function ($edit) {
            $latest = AttendanceEdit::where('attendance_id', $edit->attendance_id)
                ->latest()
                ->first();
            return $latest && $latest->id === $edit->id && $latest->status === 'pending';
        })->values();

        $approvedEdits = $edits->filter(function ($edit) {
            $latest = AttendanceEdit::where('attendance_id', $edit->attendance_id)
                ->latest()
                ->first();
            return $latest && $latest->id === $edit->id && $latest->status === 'approved';
        })->values();

        return view('user.user_application_list', compact('pendingEdits', 'approvedEdits'));
    }

    //申請一覧の詳細ボタンを押すと、申請詳細画面を表示
    public function getEditRequestDetail($id)
    {
        $edit = AttendanceEdit::with(['attendance.user', 'attendance.breaks'])->findOrFail($id);

        $attendance = $edit->attendance;
        $latestEdit = $edit; // view で使うための変数名に合わせる
        $isApplicationPage = true; // 一般ユーザーの申請詳細画面用フラグ

        return view('user.user_attendance_detail', compact('attendance', 'latestEdit', 'isApplicationPage'));
    }

    //承認待ち/承認済みの切り替えをリアルタイムで表示
    public function getEditRequestListJson()
    {
        $userId = auth()->id();

        // 承認待ち：最新の申請が pending のものだけ取得
        $pendingEdits = AttendanceEdit::with('user', 'attendance')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($edit) {
                // 同じ勤怠IDの中で自分が最新かつ pending であるものだけ残す
                $latest = AttendanceEdit::where('attendance_id', $edit->attendance_id)
                    ->latest()
                    ->first();
                return $latest && $latest->id === $edit->id && $latest->status === 'pending';
            })
            ->values(); // filter後のindexをリセット

        // 承認済み：最新の申請が approved のものだけ取得
        $approvedEdits = AttendanceEdit::with('user', 'attendance')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($edit) {
                $latest = AttendanceEdit::where('attendance_id', $edit->attendance_id)
                    ->latest()
                    ->first();
                return $latest && $latest->id === $edit->id && $latest->status === 'approved';
            })
            ->values();

        return response()->json([
            'pending' => $pendingEdits,
            'approved' => $approvedEdits,
        ]);
    }
}
