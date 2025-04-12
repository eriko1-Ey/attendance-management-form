@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_attendance_detail.css')}}?v={{ time() }}" />
@endsection

@section('title','勤怠詳細')

@php $headerType = 'user'; @endphp

@section('content')
<div class="container">
    <h2 class="title">勤怠詳細</h2>

    <div class="details-box">
        <form method="POST" action="{{ route('user.attendance.editRequest', ['id' => $attendance->id]) }}">
            @csrf
            <table class="details-table">
                <tr>
                    <th>名前</th>
                    <td>{{ $attendance->user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y年n月j日') }}</td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="time" name="clock_in" value="{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}" @if($latestEdit->status === 'approved') disabled @endif />
                        〜
                        <input type="time" name="clock_out" value="{{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}" @if($latestEdit->status === 'approved') disabled @endif/>
                    </td>
                </tr>
                <tr>
                    <th>休憩</th>
                    <td>
                        @foreach($attendance->breaks as $i => $break)
                        <div>
                            <input type="time" name="breaks[{{ $i }}][start_time]" value="{{ \Carbon\Carbon::parse($break->start_time)->format('H:i') }}" @if($latestEdit->status === 'approved') disabled @endif/>
                            〜
                            <input type="time" name="breaks[{{ $i }}][end_time]" value="{{ \Carbon\Carbon::parse($break->end_time)->format('H:i') }}" @if($latestEdit->status === 'approved') disabled @endif/>
                        </div>
                        @endforeach
                        @if (!isset($latestEdit) || $latestEdit->status !== 'approved')
                        <div>
                            <input type="time" name="breaks[{{ count($attendance->breaks) }}][start_time]" />
                            〜
                            <input type="time" name="breaks[{{ count($attendance->breaks) }}][end_time]" />
                        </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="reason" required
                            @if(isset($latestEdit) && $latestEdit->status === 'approved') disabled @endif
        >{{ old('reason', $latestEdit->reason ?? '') }}</textarea>
                    </td>
                </tr>
            </table>
    </div>

    @if (isset($isApplicationPage) && $isApplicationPage)
    @if ($latestEdit->status === 'pending')
    <p class="text-warning">承認待ちのため修正はできません。</p>
    @elseif ($latestEdit->status === 'approved')
    <button type="button" class="approved-button" disabled>承認済み</button>
    @endif
    @else
    <button type="submit" class="edit-button">修正</button>
    @endif
    </form>
</div>
@endsection