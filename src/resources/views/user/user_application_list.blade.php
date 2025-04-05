@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/user_application_list.css')}}" />
@endsection

@section('title','申請一覧')

@php $headerType = 'user'; @endphp

@section('content')
<div class="container">
    <h2 class="title">申請一覧</h2>

    <!-- タブ -->
    <div class="tab-container">
        <div class="tab active" data-target="pending">承認待ち</div>
        <div class="tab" data-target="approved">承認済み</div>
    </div>

    <!-- 申請待ちリスト -->
    <div class="application-list-box tab-content active" id="pending">
        <table class="application-table">
            <thead>
                <tr>
                    <th>申請種別</th>
                    <th>名前</th>
                    <th>対象日</th>
                    <th>申請理由</th>
                    <th>申請日</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingEdits as $edit)
                <tr>
                    <td>勤怠修正</td>
                    <td>{{ $edit->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($edit->attendance->date)->format('Y/m/d') }}</td>
                    <td>{{ $edit->reason }}</td>
                    <td>{{ \Carbon\Carbon::parse($edit->created_at)->format('Y/m/d') }}</td>
                    <td><a href="#">詳細</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 承認済みリスト -->
    <div class="application-list-box tab-content" id="approved">
        <table class="application-table">
            <thead>
                <tr>
                    <th>申請種別</th>
                    <th>名前</th>
                    <th>対象日</th>
                    <th>申請理由</th>
                    <th>申請日</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvedEdits as $edit)
                <tr>
                    <td>勤怠修正</td>
                    <td>{{ $edit->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($edit->attendance->date)->format('Y/m/d') }}</td>
                    <td>{{ $edit->reason }}</td>
                    <td>{{ \Carbon\Carbon::parse($edit->created_at)->format('Y/m/d') }}</td>
                    <td><a href="#">詳細</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const tabs = document.querySelectorAll(".tab");
                const tabContents = document.querySelectorAll(".tab-content");

                tabs.forEach((tab) => {
                    tab.addEventListener("click", function() {
                        // タブのアクティブ状態を変更
                        tabs.forEach((t) => t.classList.remove("active"));
                        this.classList.add("active");

                        // 対応するコンテンツの表示を変更
                        const target = this.getAttribute("data-target");
                        tabContents.forEach((content) => {
                            content.classList.remove("active");
                            if (content.id === target) {
                                content.classList.add("active");
                            }
                        });
                    });
                });
            });
        </script>
    </div>
</div>
@endsection