<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        // 対象日数（例：過去7日間）
        $days = 7;

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($i)->toDateString();

            // 同日に勤務させるユーザーをランダムに3人選ぶ（最低3人）
            $workingUsers = $users->random(3);

            foreach ($workingUsers as $user) {
                $clockIn = Carbon::parse("$date 09:00:00");
                $clockOut = Carbon::parse("$date 18:00:00");

                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date,
                    'status' => 'after_work',
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                ]);
            }
        }

        // 他のユーザーにもランダムで出勤させたいならここで追加可能
    }
}
