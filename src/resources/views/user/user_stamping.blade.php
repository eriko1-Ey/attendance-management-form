@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_stamping.css')}}" />
@endsection

@section('title','勤怠登録')

@php $headerType = 'user'; @endphp

@section('content')
<div class="attendance-container">
    <div class="status-badge">勤務外</div>
    <h2 class="date">2023年6月1日(木)</h2>
    <h1 class="time">08:00</h1>
    <button class="attendance-button">出勤</button>
</div>
@endsection