<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\AttendanceEdit;
use App\Models\BreakRecord;
use App\Models\User;
use Carbon\Carbon;

class UserAttendanceController extends Controller
{
    // 出勤登録画面表示
    public function getStamping()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        return view('user.user_stamping', compact('attendance'));
    }

    // 出勤処理
    public function clockIn()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        //調べる。レコードを取得するか作成する。
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['clock_in' => Carbon::now(), 'status' => 'working']
        );

        if (!$attendance->wasRecentlyCreated && $attendance->clock_in) {
            return redirect()->back()->with('error', '既に出勤済みです。');
        }

        //確認が必要。（重複している可能性がある）
        $attendance->update([
            'clock_in' => Carbon::now(),
            'status' => 'working'
        ]);

        return redirect()->route('getStamping')->with('success', '出勤しました。');
    }

    // 退勤処理
    public function clockOut()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance && !$attendance->clock_out) {
            $attendance->update([
                'clock_out' => Carbon::now(),
                'status' => 'after_work'
            ]);

            return redirect()->route('getStamping')->with('success', '退勤しました。お疲れ様でした。');
        }

        return redirect()->back()->with('error', '退勤処理ができませんでした。');
    }

    // 休憩開始処理
    public function startBreak()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance && $attendance->status === 'working') {
            $attendance->update(['status' => 'on_break']);
            $attendance->breaks()->create(['start_time' => Carbon::now()]);

            return redirect()->route('getStamping')->with('success', '休憩を開始しました。');
        }

        return redirect()->back()->with('error', '休憩開始処理ができませんでした。');
    }

    // 休憩終了処理
    public function endBreak()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance && $attendance->status === 'on_break') {
            $attendance->update(['status' => 'working']);
            $latestBreak = $attendance->breaks()->latest()->first();
            if ($latestBreak && !$latestBreak->end_time) {
                $latestBreak->update(['end_time' => Carbon::now()]);
            }

            return redirect()->route('getStamping')->with('success', '休憩を終了しました。');
        }

        return redirect()->back()->with('error', '休憩終了処理ができませんでした。');
    }

    // 勤怠一覧表示
    public function attendanceList(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month') ?? Carbon::now()->format('Y-m');

        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        // 月の日付リストを作成
        $dates = [];
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->copy();
        }

        // 勤怠データ取得 → 日付をキーにしたマップに変換
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('breaks')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item];
            });

        return view('user.user_attendance_record', compact('dates', 'attendances', 'month'));
    }

    //勤怠詳細画面表示
    public function getDetail($id)
    {
        $attendance = Attendance::with('breaks', 'user')->findOrFail($id);

        return view('user.user_attendance_detail', compact('attendance'));
    }

    // 修正申請処理
    public function postEditRequest(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // 勤怠の日付を取得して、時刻と結合
        $date = $attendance->date;
        $clockIn = Carbon::parse($date . ' ' . $request->clock_in);
        $clockOut = Carbon::parse($date . ' ' . $request->clock_out);

        // 勤怠情報の更新
        $attendance->update([
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
        ]);

        // 既存の休憩データを削除
        $attendance->breaks()->delete();

        // 新しい休憩データを作成
        if ($request->has('breaks')) {
            foreach ($request->breaks as $break) {
                if (!empty($break['start_time']) && !empty($break['end_time'])) {
                    $attendance->breaks()->create([
                        'start_time' => Carbon::parse($date . ' ' . $break['start_time']),
                        'end_time' => Carbon::parse($date . ' ' . $break['end_time']),
                    ]);
                }
            }
        }

        // 修正申請の登録
        AttendanceEdit::create([
            'user_id' => Auth::id(),
            'attendance_id' => $attendance->id,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);

        // 修正内容を保存（必要に応じて申請に保存だけでも可）
        $attendance->clock_in = $clockIn;
        $attendance->clock_out = $clockOut;
        $attendance->save();

        // 修正申請の登録
        AttendanceEdit::create([
            'user_id' => Auth::id(),
            'attendance_id' => $attendance->id,
            'status' => 'pending',
            'reason' => $request->reason
        ]);

        return redirect()->route('user.attendance.postRequestList')->with('success', '修正申請を送信しました');
    }
}
