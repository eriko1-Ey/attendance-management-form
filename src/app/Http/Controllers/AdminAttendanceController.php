<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceEdit;
use App\Models\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminAttendanceController extends Controller
{
    // 勤怠一覧表示
    public function getAdminAttendanceRecord(Request $request)
    {
        // クエリパラメータから日付を取得し、指定がなければ本日の日付を設定
        $date = $request->query('date', Carbon::today()->toDateString());

        // 指定された日付の勤怠情報を取得
        $attendances = Attendance::with('user')
            ->whereDate('date', $date)
            ->orderBy('clock_in', 'asc')
            ->get();

        return view('admin.admin_daily_attendance_record', compact('attendances', 'date'));
    }

    //スタッフの月次勤怠取得＿スタッフ一覧の詳細ボタンから
    public function getUserAttendanceRecord(Request $request, $user_id)
    {
        // 対象ユーザーを取得
        $user = User::findOrFail($user_id);

        // 表示する月の取得（指定がなければ今月）
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);

        // その月の全勤怠データ
        $attendances = Attendance::with('breaks')
            ->where('user_id', $user_id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        // 月初から月末までの日付を生成
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $dateRange = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateRange[] = $date->format('Y-m-d');
        }

        return view('admin.admin_user_attendance_record', compact('user', 'attendances', 'year', 'month', 'dateRange'));
    }

    //csv出力
    public function exportCsv(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);

        $attendances = Attendance::with('breaks')
            ->where('user_id', $user_id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $fileName = "{$user->name}_{$year}年{$month}月_勤怠.csv";

        $response = new StreamedResponse(function () use ($startDate, $endDate, $attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['日付', '出勤', '退勤', '休憩', '労働時間']);

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                $attendance = $attendances[$dateStr] ?? null;

                $clockIn = $attendance && $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-';
                $clockOut = $attendance && $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-';

                $breakTime = $attendance && $attendance->breaks ? $attendance->breaks->sum(function ($break) {
                    return \Carbon\Carbon::parse($break->end_time)->diffInMinutes(\Carbon\Carbon::parse($break->start_time));
                }) : 0;

                $totalMinutes = ($attendance && $attendance->clock_in && $attendance->clock_out)
                    ? \Carbon\Carbon::parse($attendance->clock_out)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_in)) - $breakTime
                    : 0;

                $breakFormatted = $breakTime > 0 ? sprintf('%02d:%02d', floor($breakTime / 60), $breakTime % 60) : '-';
                $workFormatted = $totalMinutes > 0 ? sprintf('%02d:%02d', floor($totalMinutes / 60), $totalMinutes % 60) : '-';

                fputcsv($handle, [$dateStr, $clockIn, $clockOut, $breakFormatted, $workFormatted]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    //該当の一般ユーザーの月次勤怠一覧の詳細ボタンを押した際の挙動
    //①詳細画面の表示
    public function editAttendance($id)
    {
        $attendance = Attendance::with(['user', 'breaks', 'attendanceEdits' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        $latestEdit = $attendance->attendanceEdits->first();

        return view('admin.admin_attendance_detail', compact('attendance', 'latestEdit'));
    }


    //②詳細画面の編集内容の保存
    public function updateAttendance(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'reason' => 'required|string|max:255', // ← 修正理由は必須に
            'breaks.*.start' => 'nullable|date_format:H:i',
            'breaks.*.end' => 'nullable|date_format:H:i',
        ]);

        $date = $attendance->date;

        // 勤怠の出退勤時間を修正
        $attendance->clock_in = $request->clock_in ? Carbon::parse($date . ' ' . $request->clock_in) : null;
        $attendance->clock_out = $request->clock_out ? Carbon::parse($date . ' ' . $request->clock_out) : null;
        $attendance->save();

        // 既存の休憩を削除→再登録
        $attendance->breaks()->delete();
        if ($request->has('breaks')) {
            foreach ($request->breaks as $break) {
                if (!empty($break['start']) && !empty($break['end'])) {
                    $attendance->breaks()->create([
                        'start_time' => Carbon::parse($date . ' ' . $break['start']),
                        'end_time' => Carbon::parse($date . ' ' . $break['end']),
                    ]);
                }
            }
        }

        // 管理者による修正履歴を attendance_edits に保存
        AttendanceEdit::create([
            'user_id' => $attendance->user_id,
            'attendance_id' => $attendance->id,
            'status' => 'approved', // 管理者が直接修正した場合は「承認済み」で保存
            'reason' => $request->reason,
        ]);

        return redirect()->route('getUserAttendanceRecord', ['user_id' => $attendance->user_id])
            ->with('success', '勤怠情報を更新しました。');
    }
}
