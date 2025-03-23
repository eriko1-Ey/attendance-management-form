@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_stamping.css')}}" />
@endsection

@section('title','勤怠登録')

@php $headerType = 'user';
use Carbon\Carbon;

$today = \Carbon\Carbon::now()->format('Y年n月j日(D)');
$now = \Carbon\Carbon::now()->format('H:i');
$status = $attendance->status ?? 'before_work';
$statusText = [
'before_work' => '勤務外',
'working' => '出勤中',
'on_break' => '休憩中',
'after_work' => '退勤済',
];
@endphp

@section('content')
<div class="attendance-container">
    <div class="status-badge">{{ $statusText[$attendance->status ?? 'before_work'] }}</div>
    <h2 class="date">{{ $today }}</h2>
    <h1 class="time">{{ $now }}</h1>

    @if ($status === 'before_work')
    <form action="{{ route('user.attendance.clockIn') }}" method="POST">
        @csrf
        <button type="submit" class="attendance-button">出勤</button>
    </form>
    @elseif ($status === 'working')
    <form action="{{ route('user.attendance.startBreak') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="attendance-button">休憩入</button>
    </form>
    <form action="{{ route('user.attendance.clockOut') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="attendance-button">退勤</button>
    </form>
    @elseif ($status === 'on_break')
    <form action="{{ route('user.attendance.endBreak') }}" method="POST">
        @csrf
        <button type="submit" class="attendance-button">休憩戻</button>
    </form>
    @elseif ($status === 'after_work')
    <p class="thanks-message">お疲れ様でした！</p>
    @endif
</div>
@endsection