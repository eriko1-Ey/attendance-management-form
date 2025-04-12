<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceEdit;

class AdminApplicationListController extends Controller
{
    //申請一覧を表示（すべての一般ユーザーの未承認のものを取得して表示）
    public function getAdminEditRequestList()
    {
        // 全申請を取得（ユーザー・勤怠情報つき）
        $edits = AttendanceEdit::with(['user', 'attendance'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 勤怠ごとに最新の申請だけ取得
        $latestEdits = $edits->groupBy('attendance_id')
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->first(); // 最新1件のみ
            });

        // ステータスで分ける
        $pendingEdits = $latestEdits->filter(fn($edit) => $edit->status === 'pending')->values();
        $approvedEdits = $latestEdits->filter(fn($edit) => $edit->status === 'approved')->values();

        return view('admin.admin_application_list', compact('pendingEdits', 'approvedEdits'));
    }

    //承認画面表示
    public function getApprovalPage($id)
    {
        // 修正申請情報と勤怠情報・ユーザー情報を取得
        //IDが$idの申請をさがして、申請者、勤怠情報、その勤怠の休憩を全部一緒に取得する。
        //findOrFail($id):そのidのデータを探して、無ければ404エラーを自動で返す。
        $edit = AttendanceEdit::with(['user', 'attendance.breaks'])->findOrFail($id);

        $attendance = $edit->attendance;
        $attendance->load('user', 'breaks'); // 念のため再読込（user, breaks）
        $latestEdit = $edit; // 画面側が期待する変数名に合わせる

        // 「承認画面」として表示するフラグを渡す（ボタン切り替えに使う）
        $isApprovalPage = true;

        return view('admin.admin_attendance_detail', compact('attendance', 'latestEdit', 'isApprovalPage'));
    }

    //承認画面処理
    public function approveEdit(Request $request, $id)
    {
        $edit = AttendanceEdit::with('attendance.user', 'attendance.breaks')->findOrFail($id);
        $attendance = $edit->attendance;

        // 勤怠データに申請内容を反映
        $attendance->clock_in = $edit->new_clock_in;
        $attendance->clock_out = $edit->new_clock_out;
        $attendance->save();

        // ステータス変更
        $edit->status = 'approved';
        $edit->save();

        // 詳細画面に戻ってボタンを「承認済み」に切り替えるため、再表示に必要な情報を渡す
        $latestEdit = $edit;
        $isApprovalPage = true;

        return redirect()->route('getAdminEditRequestList')->with('success', '申請を承認しました。');
    }

    public function getAdminEditRequestListJson()
    {
        $edits = AttendanceEdit::with(['user', 'attendance'])
            ->orderBy('created_at', 'desc')
            ->get();

        $latestEdits = $edits->groupBy('attendance_id')
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->first();
            });

        $pendingEdits = $latestEdits->filter(fn($edit) => $edit->status === 'pending')->values();
        $approvedEdits = $latestEdits->filter(fn($edit) => $edit->status === 'approved')->values();

        return response()->json([
            'pending' => $pendingEdits,
            'approved' => $approvedEdits,
        ]);
    }
}
