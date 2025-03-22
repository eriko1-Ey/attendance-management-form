@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/staff_attendance_detail.css')}}" />
@endsection

@section('title','勤怠詳細')

@php $headerType = 'user'; @endphp

@section('content')
<div class="container">
    <h2 class="title">勤怠詳細</h2>

    <div class="details-box">
        <table class="details-table">
            <tr>
                <th>名前</th>
                <td>西 伶奈</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>2023年 6月1日</td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    <input type="text" value="09:00" readonly /> 〜
                    <input type="text" value="18:00" readonly />
                </td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    <input type="text" value="12:00" readonly /> 〜
                    <input type="text" value="13:00" readonly />
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td>
                    <textarea readonly>電車遅延のため</textarea>
                </td>
            </tr>
        </table>
    </div>

    <button class="edit-button">修正</button>
</div>
@endsection