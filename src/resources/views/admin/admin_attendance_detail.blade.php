@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_attendance_detail.css')}}?v={{ time() }}" />
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
                        <input type="time" name="clock_in"
                            value="{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}"
                            @if(isset($latestEdit) && $latestEdit->status === 'approved') disabled @endif />
                        〜
                        <input type="time" name="clock_out"
                            value="{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}"
                            @if(isset($latestEdit) && $latestEdit->status === 'approved') disabled @endif />
                    </td>
                </tr>
                <tr>
                    <th>休憩</th>
                    <td>
                        @foreach ($attendance->breaks as $index => $break)
                        <div class="break-time-group">
                            <input type="time" name="breaks[{{ $index }}][start]"
                                value="{{ \Carbon\Carbon::parse($break->start_time)->format('H:i') }}"
                                @if(isset($latestEdit) && $latestEdit->status === 'approved') disabled @endif />
                            〜
                            <input type="time" name="breaks[{{ $index }}][end]"
                                value="{{ \Carbon\Carbon::parse($break->end_time)->format('H:i') }}"
                                @if(isset($latestEdit) && $latestEdit->status === 'approved') disabled @endif />
                        </div>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea
                            name="reason"
                            @if(isset($latestEdit) && $latestEdit->status === 'approved') disabled @endif>
    {{ old('reason', optional($latestEdit)->reason) }}
</textarea>
                    </td>
                </tr>
            </table>
        </div>

        @if (isset($isApprovalPage) && $isApprovalPage)
        @if ($latestEdit->status === 'approved')
        <button type="button" class="approved-button" disabled>承認済み</button>
        @else
        <form action="{{ route('approveEdit', ['id' => $latestEdit->id]) }}" method="POST">
            @csrf
            <button type="submit" class="edit-button">承認</button>
        </form>
        @endif
        @else
        <form action="{{ route('admin.attendance.update', ['id' => $attendance->id]) }}" method="POST">
            @csrf
            <button type="submit" class="edit-button">修正</button>
        </form>
        @endif
    </form>
</div>
@endsection