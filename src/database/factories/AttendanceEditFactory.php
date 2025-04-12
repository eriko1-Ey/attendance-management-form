<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\AttendanceEdit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceEditFactory extends Factory
{
    protected $model = AttendanceEdit::class;

    public function definition(): array
    {
        $reasons = [
            '電車遅延のため',
            '病欠のため',
            '私用のため',
            '体調不良のため',
            '通院のため',
            '家庭の事情のため',
            '会議延長のため',
            'システムトラブルのため',
        ];

        $faker = \Faker\Factory::create('ja_JP');
        $newClockIn = $this->faker->dateTimeBetween('-1 month', 'now');
        $newClockOut = (clone $newClockIn)->modify('+8 hours');

        return [
            'user_id' => User::factory(), // 後で上書き
            'attendance_id' => Attendance::factory(), // 後で上書き
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reason' => $faker->randomElement($reasons),
            'new_clock_in' => $newClockIn,
            'new_clock_out' => $newClockOut,
        ];
    }
}
