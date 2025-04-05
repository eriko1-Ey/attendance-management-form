@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin_daily_attendance_record.css')}}" />
@endsection

@section('title','管理者用勤怠一覧')

@php $headerType = 'admin'; @endphp

@section('content')
<div class="container">
    <h2 class="title">{{ \Carbon\Carbon::parse($date)->format('Y年m月d日') }}の勤怠</h2>

    <!-- 日付選択 -->
    <div class="date-selector">
        <a href="{{ route('getAdminAttendanceRecord', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}" class="btn btn-primary">&lt; 前日</a>
        <input type="date" id="attendance-date" class="current-date" value="{{ $date }}" />
        <a href="{{ route('getAdminAttendanceRecord', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}" class="btn btn-primary">翌日 &gt;</a>
    </div>

    <div class="attendance-list-box">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody id="attendance-table-body">
                @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                    <td>
                        @php
                        $breakTime = $attendance->breaks->sum(function($break) {
                        return \Carbon\Carbon::parse($break->end_time)->diffInMinutes(\Carbon\Carbon::parse($break->start_time));
                        });
                        $breakHours = floor($breakTime / 60);
                        $breakMinutes = $breakTime % 60;
                        @endphp
                        {{ sprintf('%02d:%02d', $breakHours, $breakMinutes) }}
                    </td>
                    <td>
                        @php
                        if ($attendance->clock_in && $attendance->clock_out) {
                        $workTime = \Carbon\Carbon::parse($attendance->clock_out)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_in)) - $breakTime;
                        $workHours = floor($workTime / 60);
                        $workMinutes = $workTime % 60;
                        echo sprintf('%02d:%02d', $workHours, $workMinutes);
                        } else {
                        echo '-';
                        }
                        @endphp
                    </td>
                    <td><a href="{{ route('admin.attendance.edit', ['id' => $attendance->id]) }}">詳細</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dateInput = document.getElementById("attendance-date");

        // カレンダーで日付変更
        dateInput.addEventListener("change", function() {
            const selectedDate = this.value;
            window.location.href = "{{ route('getAdminAttendanceRecord') }}?date=" + selectedDate;
        });
    });
</script>
@endsection