@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_register.css')}}" />
@endsection

@section('title','会員登録')

@php $headerType = 'common'; @endphp

@section('content')
<div class="register-container">
    <h2 class="register-title">会員登録</h2>

    <form action="{{ route('postRegister') }}" method="POST">
        @csrf
        <div class="input-group">
            <label for="name">名前</label>
            <input type="text" id="name" name="name" required />
        </div>

        <div class="input-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" required />
        </div>

        <div class="input-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required />
        </div>

        <div class="input-group">
            <label for="password_confirmation">パスワード確認</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required />
        </div>

        <button type="submit" class="register-button">登録する</button>

        <p class="login-link">
            <a href="/login">ログインはこちら</a>
        </p>
    </form>
</div>
@endsection