<header class="header">
    <div class="logo">COACHTECH</div>
    <nav class="nav">
        <a href="{{ route('getStamping') }}">勤怠</a>
        <a href="{{ route('user.attendance.list') }}">勤怠一覧</a>
        <a href="#">申請</a>
        <form action="{{ route('logout') }}" method="post">
            <a href="#">ログアウト</a>
        </form>
    </nav>
</header>