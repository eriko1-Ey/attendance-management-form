<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceEdit;
use Illuminate\Database\Seeder;

class AttendanceEditSeeder extends Seeder
{
    public function run(): void
    {
        // 一部の勤怠データに対して修正申請を作成（ランダム）
        Attendance::inRandomOrder()->take(20)->get()->each(function ($attendance) {
            AttendanceEdit::factory()->create([
                'user_id' => $attendance->user_id,
                'attendance_id' => $attendance->id,
            ]);
        });
    }
}
