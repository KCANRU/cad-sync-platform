<?php

namespace Database\Seeders;

use App\Models\StageConfig;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        StageConfig::create([
            'stage_code' => 'RAW',
            'stage_name' => 'Mühendislik Çizimi',
            'target_view' => 'engineering_screen',
            'validation_rules' => null,
            'allowed_tags' => ['LINE_NO', 'SPEC', 'DESIGN_PRESSURE'],
            'inherit_from_parent' => false,
            'can_edit_geometry' => false,
            'active' => true,
        ]);

        StageConfig::create([
            'stage_code' => 'FIELD',
            'stage_name' => 'Saha Uyarlamalı',
            'target_view' => 'field_screen',
            'validation_rules' => null,
            'allowed_tags' => ['JOINT_NO', 'WELDER', 'TEST_PKG', 'INSPECTOR'],
            'inherit_from_parent' => true,
            'can_edit_geometry' => false,
            'active' => true,
        ]);

        StageConfig::create([
            'stage_code' => 'ASBUILT',
            'stage_name' => 'As-Built',
            'target_view' => 'asbuilt_screen',
            'validation_rules' => null,
            'allowed_tags' => ['AS_BUILT_BY', 'DATE', 'REMARKS'],
            'inherit_from_parent' => true,
            'can_edit_geometry' => false,
            'active' => true,
        ]);

        StageConfig::create([
            'stage_code' => 'SUPPORT',
            'stage_name' => 'Mesnet/Destek',
            'target_view' => 'support_screen',
            'validation_rules' => null,
            'allowed_tags' => ['SUPPORT_TYPE', 'LOAD_CLASS', 'ANCHOR_BOLT'],
            'inherit_from_parent' => false,
            'can_edit_geometry' => false,
            'active' => true,
        ]);
    }
}
