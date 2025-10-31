<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 標準で含まれている User::factory()->create() や UserSeeder::class の呼び出しを削除
        $this->call([
            ClassesSeeder::class,
            AdminSeeder::class,
            TestDatabaseSeeder::class,
        ]);
    }
}
