<?php

namespace Database\Seeders;

use App\Models\ReportPeriod;
use App\Models\Student;
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
        $users = [
            [
                'name' => 'Admin Kurikulum',
                'username' => 'KurikulumWikrama',
                'password' => Hash::make('Wikrama2022'),
                'role' => User::ADMIN,
                'status' => true,
                'userable_id' => Teacher::factory(['name' => 'Admin Kurikulum'])->create()->id,
            ],
            [
                'name' => 'Siswa 1',
                'username' => 'Siswa1',
                'password' => Hash::make('Wikrama2022'),
                'role' => User::STUDENT,
                'status' => true,
                'userable_id' => Student::factory(['name' => 'Siswa 1'])->create()->id,
            ],
            [
                'name' => 'Siswa 2',
                'username' => 'Siswa2',
                'password' => Hash::make('Wikrama2022'),
                'role' => User::STUDENT,
                'status' => true,
                'userable_id' => Student::factory(['name' => 'Siswa 2'])->create()->id,
            ],
            [
                'name' => 'Siswa 3',
                'username' => 'Siswa3',
                'password' => Hash::make('Wikrama2022'),
                'role' => User::STUDENT,
                'status' => true,
                'userable_id' => Student::factory(['name' => 'Siswa 3'])->create()->id,
            ],
            [
                'name' => 'Guru 1',
                'username' => 'Guru1',
                'password' => Hash::make('Wikrama2022'),
                'role' => User::TEACHER,
                'status' => true,
                'userable_id' => Teacher::factory(['name' => 'Guru 1'])->create()->id,
            ],
            [
                'name' => 'Guru 2',
                'username' => 'Guru2',
                'password' => Hash::make('Wikrama2022'),
                'role' => User::TEACHER,
                'status' => true,
                'userable_id' => Teacher::factory(['name' => 'Guru 2'])->create()->id,
            ],
        ];

        foreach ($users as $user) {
            User::factory($user)->create();
        }

        Subject::factory(['name' => 'Bahasa Indonesia', 'group' => Subject::MUATAN_A])->create();
        Subject::factory(['name' => 'Bahasa Sunda', 'group' => Subject::MUATAN_B])->create();
        Subject::factory(['name' => 'Pemrograman Dasar', 'group' => Subject::MUATAN_C])->create();

        $reportPeriods = [
            [
                'title' => 'Tengah Semester Ganjil',
                'school_year' => '2021/2022',
            ],
            [
                'title' => 'Semester Ganjil',
                'school_year' => '2021/2022',
            ],
            [
                'title' => 'Tengah Semester Genap',
                'school_year' => '2021/2022',
            ],
            [
                'title' => 'Semester Genap',
                'school_year' => '2021/2022',
            ],
        ];

        foreach($reportPeriods as $reportPeriod){
            ReportPeriod::factory($reportPeriod)->create();
        }
    }
}
