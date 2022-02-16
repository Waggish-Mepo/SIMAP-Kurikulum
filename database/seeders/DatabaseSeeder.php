<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'name' => 'Admin Kurikulum',
            'username' => 'KurikulumWikrama',
            'password' => Hash::make('Wikrama2022'),
            'role' => User::ADMIN,
            'status' => true,
            'userable_id' => Teacher::factory(['name' => 'Admin Kurikulum'])->id,
        ];

        User::factory($user)->create();

        // Subject::factory(['name' => 'Bahasa Indonesia'])->count(3)->create();

    }
}
