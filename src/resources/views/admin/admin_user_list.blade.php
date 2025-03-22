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
                    <tr>
                        <td>白鳥 玲奈</td>
                        <td>reina@coachtech.com</td>
                        <td><a href="#">詳細</a></td>
                    </tr>
                    <tr>
                        <td>山田 太郎</td>
                        <td>taro.y@coachtech.com</td>
                        <td><a href="#">詳細</a></td>
                    </tr>
                    <tr>
                        <td>田中 一郎</td>
                        <td>ichiro.t@coachtech.com</td>
                        <td><a href="#">詳細</a></td>
                    </tr>
                    <tr>
                        <td>山本 啓一</td>
                        <td>keiichi.y@coachtech.com</td>
                        <td><a href="#">詳細</a></td>
                    </tr>
                    <tr>
                        <td>松田 智美</td>
                        <td>tomomi.m@coachtech.com</td>
                        <td><a href="#">詳細</a></td>
                    </tr>
                    <tr>
                        <td>中島 則夫</td>
                        <td>norio.n@coachtech.com</td>
                        <td><a href="#">詳細</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection