<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\BreakRecord;
use Illuminate\Database\Seeder;

class BreakRecordSeeder extends Seeder
{
    public function run(): void
    {
        // 各勤怠に1〜3件の休憩記録を追加
        Attendance::all()->each(function ($attendance) {
            BreakRecord::factory(rand(1, 3))->create([
                'attendance_id' => $attendance->id,
            ]);
        });
    }
}
