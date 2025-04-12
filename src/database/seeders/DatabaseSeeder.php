<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        app()->setLocale('ja'); // Laravelのロケール変更
        $faker = FakerFactory::create('ja_JP'); // Fakerのロケール変更

        $this->call([
            UserSeeder::class,
            AttendanceSeeder::class,
            AttendanceEditSeeder::class,
            BreakRecordSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
