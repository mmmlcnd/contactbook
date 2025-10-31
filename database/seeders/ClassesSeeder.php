<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        Classes::create(['grade' => 1, 'name' => 'A組']);
        Classes::create(['grade' => 1, 'name' => 'B組']);

        Classes::create(['grade' => 2, 'name' => 'A組']);
        Classes::create(['grade' => 2, 'name' => 'B組']);

        Classes::create(['grade' => 3, 'name' => 'A組']);
        Classes::create(['grade' => 3, 'name' => 'B組']);
    }
}
