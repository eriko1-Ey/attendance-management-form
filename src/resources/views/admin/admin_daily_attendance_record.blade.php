@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin_daily_attendance_record.css')}}" />
@endsection

@section('title','管理者用勤怠一覧')

@php $headerType = 'admin'; @endphp

@section('content')
<div class="container">
    <h2 class="title" id="attendance-title"></h2>

    <!-- 日付選択 -->
    <div class="date-selector">
        <button class="prev-day">&lt; 前日</button>
        <input type="date" id="attendance-date" class="current-date" />
        <button class="next-day">翌日 &gt;</button>
    </div>

    <div class="attendance-list-box">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody id="attendance-table-body">
                <!-- JavaScriptで動的にデータを追加 -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const dateInput = document.getElementById("attendance-date");
                        const attendanceTitle =
                            document.getElementById("attendance-title");
                        const prevDayButton = document.querySelector(".prev-day");
                        const nextDayButton = document.querySelector(".next-day");
                        const attendanceTableBody = document.getElementById(
                            "attendance-table-body"
                        );

                        // 初期日付を本日の日付に設定
                        let currentDate = new Date();
                        updateDate();

                        // 日付を更新
                        function updateDate() {
                            const formattedDate = formatDate(currentDate);
                            attendanceTitle.textContent = `${formattedDate}の勤怠`;
                            dateInput.value = formatDateInput(currentDate);
                            updateAttendanceData(formattedDate);
                        }

                        // 日付の書式を YYYY年M月D日 にする
                        function formatDate(date) {
                            const year = date.getFullYear();
                            const month = date.getMonth() + 1;
                            const day = date.getDate();
                            return `${year}年${month}月${day}日`;
                        }

                        // input type="date" 用のフォーマット YYYY-MM-DD
                        function formatDateInput(date) {
                            const year = date.getFullYear();
                            const month = (date.getMonth() + 1)
                                .toString()
                                .padStart(2, "0");
                            const day = date.getDate().toString().padStart(2, "0");
                            return `${year}-${month}-${day}`;
                        }

                        // 前日ボタン
                        prevDayButton.addEventListener("click", function() {
                            currentDate.setDate(currentDate.getDate() - 1);
                            updateDate();
                        });

                        // 翌日ボタン
                        nextDayButton.addEventListener("click", function() {
                            currentDate.setDate(currentDate.getDate() + 1);
                            updateDate();
                        });

                        // カレンダーで日付変更
                        dateInput.addEventListener("change", function() {
                            currentDate = new Date(this.value);
                            updateDate();
                        });

                        // 勤怠データを更新（仮のダミーデータ）
                        function updateAttendanceData(date) {
                            const dummyData = [{
                                    name: "山田 直志",
                                    start: "09:00",
                                    end: "18:00",
                                    break: "1:00",
                                    total: "8:00",
                                },
                                {
                                    name: "山田 太郎",
                                    start: "09:00",
                                    end: "18:00",
                                    break: "1:00",
                                    total: "8:00",
                                },
                                {
                                    name: "佐藤 一郎",
                                    start: "09:00",
                                    end: "18:00",
                                    break: "1:00",
                                    total: "8:00",
                                },
                                {
                                    name: "松田 智美",
                                    start: "09:00",
                                    end: "18:00",
                                    break: "1:00",
                                    total: "8:00",
                                },
                                {
                                    name: "中島 則夫",
                                    start: "09:00",
                                    end: "18:00",
                                    break: "1:00",
                                    total: "8:00",
                                },
                            ];

                            attendanceTableBody.innerHTML = dummyData
                                .map(
                                    (user) => `
            <tr>
                <td>${user.name}</td>
                <td>${user.start}</td>
                <td>${user.end}</td>
                <td>${user.break}</td>
                <td>${user.total}</td>
                <td><a href="#">詳細</a></td>
            </tr>
        `
                                )
                                .join("");
                        }
                    });
                </script>
            </tbody>
        </table>
    </div>
</div>
@endsection