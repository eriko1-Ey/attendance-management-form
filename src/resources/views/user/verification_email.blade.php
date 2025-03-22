@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/verification_email.css')}}" />
@endsection

@section('title','メール認証')

@php $headerType = 'common'; @endphp

@section('content')
<div class="container">
    <p class="message">
        登録していただいたメールアドレスに認証メールを送付しました。<br />
        メール認証を完了してください。
    </p>

    <button class="verify-button">認証はこちらから</button>
    <a href="#" class="resend-link">認証メールを再送する</a>
</div>
@endsection