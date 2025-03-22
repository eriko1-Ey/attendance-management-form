@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/staff_attendance_record.css')}}" />
@endsection

@section('title','勤怠一覧')

@php $headerType = 'user'; @endphp

@section('content')
<div class="container">
    <h2 class="title">勤怠一覧</h2>

    <!-- 月選択 -->
    <div class="month-selector">
        <button class="prev-month">&lt; 前月</button>
        <input type="month" id="currentMonth" class="current-month" />
        <button class="next-month">翌月 &gt;</button>
    </div>

    <div class="attendance-list-box">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody id="attendanceTableBody">
                <!-- JavaScriptでデータを動的に追加 -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const monthInput = document.getElementById("currentMonth");
                        const attendanceTableBody = document.getElementById(
                            "attendanceTableBody"
                        );
                        const prevMonthButton = document.querySelector(".prev-month");
                        const nextMonthButton = document.querySelector(".next-month");

                        // 初期日付を本日の日付に設定
                        let currentDate = new Date();
                        updateMonth();

                        // 月の表示を更新
                        function updateMonth() {
                            const year = currentDate.getFullYear();
                            const month = (currentDate.getMonth() + 1)
                                .toString()
                                .padStart(2, "0");
                            monthInput.value = `${year}-${month}`;
                            generateAttendanceTable(year, month);
                        }

                        // 勤怠テーブルを動的に生成
                        function generateAttendanceTable(year, month) {
                            attendanceTableBody.innerHTML = ""; // 初期化
                            const lastDay = new Date(year, month, 0).getDate();

                            for (let day = 1; day <= lastDay; day++) {
                                const date = new Date(year, month - 1, day);
                                const dayOfWeek = [
                                    "日",
                                    "月",
                                    "火",
                                    "水",
                                    "木",
                                    "金",
                                    "土",
                                ][date.getDay()];
                                const dateString = `${month.padStart(2, "0")}/${day
                        .toString()
                        .padStart(2, "0")}(${dayOfWeek})`;

                                const row = `
                <tr>
                    <td>${dateString}</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="#">詳細</a></td>
                </tr>
            `;
                                attendanceTableBody.innerHTML += row;
                            }
                        }

                        // 前月ボタン
                        prevMonthButton.addEventListener("click", function() {
                            currentDate.setMonth(currentDate.getMonth() - 1);
                            updateMonth();
                        });

                        // 翌月ボタン
                        nextMonthButton.addEventListener("click", function() {
                            currentDate.setMonth(currentDate.getMonth() + 1);
                            updateMonth();
                        });

                        // カレンダーで月を変更
                        monthInput.addEventListener("change", function() {
                            const [year, month] = this.value.split("-");
                            currentDate = new Date(year, month - 1, 1);
                            updateMonth();
                        });
                    });
                </script>
            </tbody>
        </table>
    </div>
</div>
@endsection