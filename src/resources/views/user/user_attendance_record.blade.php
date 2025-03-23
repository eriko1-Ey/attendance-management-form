@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_attendance_record.css')}}" />
@endsection

@section('title','勤怠一覧')

@php
$headerType = 'user';
use Carbon\Carbon;

$currentMonth = Carbon::parse($month);
$prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
$nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
@endphp

@section('content')
<div class="container">
    <h2 class="title">勤怠一覧</h2>

    <!-- 月選択 -->
    <div class="month-selector">
        <a href="{{ route('user.attendance.list', ['month' => $prevMonth]) }}" class="btn">&lt; 前月</a>
        <span>{{ $currentMonth->format('Y年n月') }}</span>
        <a href="{{ route('user.attendance.list', ['month' => $nextMonth]) }}" class="btn">翌月 &gt;</a>
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
                @foreach ($dates as $date)
                @php
                $attendance = $attendances[$date->toDateString()] ?? null;
                $totalBreak = 0;

                if ($attendance) {
                foreach ($attendance->breaks as $break) {
                if ($break->start_time && $break->end_time) {
                $totalBreak += Carbon::parse($break->start_time)->diffInMinutes(Carbon::parse($break->end_time));
                }
                }
                }

                $breakHours = floor($totalBreak / 60);
                $breakMinutes = $totalBreak % 60;
                @endphp
                <tr>
                    <td>{{ $date->format('n/j(D)') }}</td>
                    <td>{{ $attendance && $attendance->clock_in ? Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance && $attendance->clock_out ? Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance ? sprintf('%d:%02d', $breakHours, $breakMinutes) : '-' }}</td>
                    <td>
                        @if ($attendance && $attendance->clock_in && $attendance->clock_out)
                        @php
                        $workMinutes = Carbon::parse($attendance->clock_in)->diffInMinutes(Carbon::parse($attendance->clock_out)) - $totalBreak;
                        $workHours = floor($workMinutes / 60);
                        $workMins = $workMinutes % 60;
                        @endphp
                        {{ sprintf('%d:%02d', $workHours, $workMins) }}
                        @else
                        -
                        @endif
                    </td>
                    <td>@if ($attendance)
                        <a href="{{ route('user.attendance.detail', ['id' => $attendance->id]) }}">詳細</a>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
@endsection