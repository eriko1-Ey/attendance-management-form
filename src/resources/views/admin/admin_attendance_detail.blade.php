@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_attendance_detail.css')}}" />
@endsection

@section('title','勤怠詳細')

@php $headerType = 'admin'; @endphp

@section('content')
<div class="container">
    <h2 class="title">勤怠詳細</h2>

    <form action="{{ route('admin.attendance.update', ['id' => $attendance->id]) }}" method="POST">
        @csrf
        <div class="details-box">
            <table class="details-table">
                <tr>
                    <th>名前</th>
                    <td>{{ $attendance->user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y年m月d日') }}</td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="time" name="break_start"
                            value="{{ optional($attendance->breaks->first())->start_time ? \Carbon\Carbon::parse($attendance->breaks->first()->start_time)->format('H:i') : '' }}" />
                        〜
                        <input type="time" name="break_end"
                            value="{{ optional($attendance->breaks->first())->end_time ? \Carbon\Carbon::parse($attendance->breaks->first()->end_time)->format('H:i') : '' }}" />
                    </td>
                </tr>
                <tr>
                    <th>休憩</th>
                    <td>
                        @foreach ($attendance->breaks as $index => $break)
                        <div class="break-time-group">
                            <input type="time" name="breaks[{{ $index }}][start]" value="{{ \Carbon\Carbon::parse($break->start_time)->format('H:i') }}" />
                            〜
                            <input type="time" name="breaks[{{ $index }}][end]" value="{{ \Carbon\Carbon::parse($break->end_time)->format('H:i') }}" />
                        </div>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="reason">{{ old('reason') }}</textarea>
                    </td>
                </tr>
            </table>
        </div>

        <button type="submit" class="edit-button">修正</button>
    </form>
</div>
@endsection