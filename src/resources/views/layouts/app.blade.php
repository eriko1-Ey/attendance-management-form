<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <link rel="stylesheet" href="sanitize.css" />
    @yield('css')

    @if(isset($headerType) && $headerType === 'admin')
    <link rel="stylesheet" href="{{ asset('css/header_admin.css') }}">
    @elseif(isset($headerType) && $headerType === 'user')
    <link rel="stylesheet" href="{{ asset('css/header_user.css') }}">
    @elseif(isset($headerType) && $headerType === 'common')
    <link rel="stylesheet" href="{{ asset('css/header_common.css') }}">
    @endif
</head>

<body>
    @if(isset($headerType) && $headerType === 'admin')
    @include('includes.header_admin')
    @elseif(isset($headerType) && $headerType === 'user')
    @include('includes.header_user')
    @elseif(isset($headerType) && $headerType === 'common')
    @include('includes.header_common')
    @endif

    <main>
        @yield('content')
    </main>
</body>

</html>