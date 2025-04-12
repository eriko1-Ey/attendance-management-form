<?php

namespace Database\Factories;

use App\Models\BreakRecord;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreakRecordFactory extends Factory
{
    protected $model = BreakRecord::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 month', 'now');
        $end = (clone $start)->modify('+1 hour');

        return [
            'attendance_id' => Attendance::factory(), // 後で上書き
            'start_time' => $start,
            'end_time' => $end,
        ];
    }
}
