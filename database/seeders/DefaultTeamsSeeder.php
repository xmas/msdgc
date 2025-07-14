<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultTeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin team
        Team::firstOrCreate(
            ['name' => 'Admin', 'type' => 'admin'],
            [
                'user_id' => 1, // Will be updated when first admin is added
                'personal_team' => false,
                'type' => 'admin',
                'is_system_team' => true,
            ]
        );

        // Create Member team
        Team::firstOrCreate(
            ['name' => 'Member', 'type' => 'member'],
            [
                'user_id' => 1, // Placeholder, doesn't matter for member team
                'personal_team' => false,
                'type' => 'member',
                'is_system_team' => true,
            ]
        );
    }
}
