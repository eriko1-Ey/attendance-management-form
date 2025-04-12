<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 管理者作成
        User::factory()->create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // 任意のパスワード
            'role' => 'admin',
        ]);

        // 一般ユーザー10人作成
        User::factory(10)->create([
            'role' => 'user',
        ]);
    }
}
