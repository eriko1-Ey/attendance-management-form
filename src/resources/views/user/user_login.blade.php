@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_login.css')}}" />
@endsection

@section('title','ログイン')

@php $headerType = 'common'; @endphp

@section('content')
<div class="login-container">
    <h2 class="login-title">ログイン</h2>

    <form action="{{ route('postLogin') }}" method="POST">
        @csrf
        <div class="input-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" placeholder="メールアドレスを入力" />
        </div>
        <div style="color:red">
            @error('email')
            {{ $message }}
            @enderror
        </div>

        <div class="input-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" placeholder="パスワードを入力" />
        </div>
        <div style="color:red">
            @error('password')
            {{ $message }}
            @enderror
        </div>


        <button type="submit" class="login-button">ログインする</button>

        <p class="register-link">
            <a href="/register">会員登録はこちら</a>
        </p>
    </form>
</div>
@endsection