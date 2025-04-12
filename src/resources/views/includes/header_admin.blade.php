<header class="header">
    <a href="{{ route('getAdminLogin') }}">
        <img src="{{ asset('storage/logo.svg') }}" alt="COACHTECHロゴ" class="logo" />
    </a>
    <nav class="nav">
        <a href="{{ route('getAdminAttendanceRecord') }}">勤怠一覧</a>
        <a href="{{ route('getUserList') }}">スタッフ一覧</a>
        <a href="{{ route('getAdminEditRequestList') }}">申請一覧</a>
        <form action="{{ route('AdminLogout') }}" method="post" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">ログアウト</button>
        </form>
    </nav>
</header>