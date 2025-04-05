@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin_user_attendance_record.css')}}" />
@endsection

@section('title','勤怠一覧')

@php $headerType = 'admin'; @endphp

@section('content')
<div class="container">
    <h2 class="title">{{ $user->name }}さんの{{ $year }}年{{ $month }}月の勤怠一覧</h2>

    <!-- 月選択 -->
    <div class="month-selector">
        <a class="prev-month"
            href="{{ route('getUserAttendanceRecord', [
       'user_id' => $user->id,
       'year' => \Carbon\Carbon::create($year, $month, 1)->subMonth()->year,
       'month' => \Carbon\Carbon::create($year, $month, 1)->subMonth()->month
   ]) }}">前月</a>
        <span class="current-month">📅 {{ $year }}/{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}</span>
        <a class="next-month"
            href="{{ route('getUserAttendanceRecord', [
       'user_id' => $user->id,
       'year' => \Carbon\Carbon::create($year, $month, 1)->addMonth()->year,
       'month' => \Carbon\Carbon::create($year, $month, 1)->addMonth()->month
   ]) }}">翌月</a>
    </div>

    <div class="attendance-list-box">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody id="attendanceTableBody">
                @foreach ($dateRange as $date)
                @php
                $attendance = $attendances[$date] ?? null;

                $clockIn = isset($attendance) && $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-';
                $clockOut = isset($attendance) && $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-';

                $breakTime = isset($attendance) && $attendance->breaks ? $attendance->breaks->sum(function($break) {
                return \Carbon\Carbon::parse($break->end_time)->diffInMinutes(\Carbon\Carbon::parse($break->start_time));
                }) : 0;

                $totalMinutes = ($attendance && $attendance->clock_in && $attendance->clock_out)
                ? \Carbon\Carbon::parse($attendance->clock_out)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_in)) - $breakTime
                : 0;

                $workTime = $totalMinutes > 0 ? sprintf('%02d:%02d', floor($totalMinutes / 60), $totalMinutes % 60) : '-';

                $breakTimeFormatted = $breakTime > 0 ? sprintf('%02d:%02d', floor($breakTime / 60), $breakTime % 60) : '-';

                $formattedDate = \Carbon\Carbon::parse($date)->format('Y/m/d(D)');
                @endphp

                <tr>
                    <td>{{ $formattedDate }}</td>
                    <td>{{ $clockIn }}</td>
                    <td>{{ $clockOut }}</td>
                    <td>{{ $breakTimeFormatted }}</td>
                    <td>{{ $workTime }}</td>
                    <td>
                        @if ($attendance)
                        <a href="{{ route('admin.attendance.edit', ['id' => $attendance->id]) }}">詳細</a>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
        <div style="text-align: right; margin-top: 10px;">
            <a href="{{ route('admin.user.attendance.csv', ['user_id' => $user->id, 'year' => $year, 'month' => $month]) }}" class="btn btn-success">
                CSV出力
            </a>
        </div>
    </div>
</div>

@endsection