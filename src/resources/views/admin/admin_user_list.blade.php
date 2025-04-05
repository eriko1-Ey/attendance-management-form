@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin_user_list.css')}}" />
@endsection

@section('title','スタッフ一覧')

@php $headerType = 'admin'; @endphp

@section('content')
<div class="container">
    <div class="container">
        <h2 class="title">スタッフ一覧</h2>
        <div class="staff-list-box">
            <table class="staff-table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>メールアドレス</th>
                        <th>月次勤怠</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('getUserAttendanceRecord', ['user_id' => $user->id]) }}">詳細</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection