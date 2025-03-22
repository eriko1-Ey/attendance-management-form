@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin_login.css')}}" />
@endsection

@section('title','管理者ログイン')

@php $headerType = 'common'; @endphp

@section('content')
<div class="login-container">
    <h2 class="login-title">管理者ログイン</h2>

    <form action="#" method="POST">
        <div class="input-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" required />
        </div>

        <div class="input-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required />
        </div>

        <button type="submit" class="login-button">管理者ログインする</button>
    </form>
</div>
@endsection