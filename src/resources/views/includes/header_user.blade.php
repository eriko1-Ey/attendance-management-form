<header class="header">
    <a href="{{ route('login') }}">
        <img src="{{ asset('storage/logo.svg') }}" alt="COACHTECHロゴ" class="logo" />
    </a>
    <nav class="nav">
        <a href="{{ route('getStamping') }}">勤怠</a>
        <a href="{{ route('user.attendance.list') }}">勤怠一覧</a>
        <a href="{{ route('user.attendance.postRequestList') }}">申請</a>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="logout-btn">ログアウト</button>
        </form>
    </nav>
</header>