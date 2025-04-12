<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $clockIn = $this->faker->dateTimeBetween('-1 month', 'now');
        $clockOut = (clone $clockIn)->modify('+8 hours');

        return [
            'user_id' => User::factory(), // 後で上書きする
            'date' => $clockIn->format('Y-m-d'),
            'status' => $this->faker->randomElement(['before_work', 'working', 'on_break', 'after_work']),
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
        ];
    }
}
