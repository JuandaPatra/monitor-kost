<?php

namespace Database\Seeders;

use App\Models\Leads;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Leads::factory()->count(10)->create();
    }
}
