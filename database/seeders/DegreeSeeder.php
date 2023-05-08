<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Degree::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Bachelor', 'max_year' => 4],
                ['name' => 'Master', 'max_year' => 2],
                ['name' => 'PhD', 'max_year' => 4],
            )
            ->create();
    }
}
