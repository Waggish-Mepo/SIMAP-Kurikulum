<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Major;
use App\Models\ReportPeriod;
use App\Models\Student;
use App\Models\StudentGroup;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Service\Functions\AcademicCalendar;
use Faker\Factory;
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

        $faker = Factory::create();

        $users = [
            [
                'name' => 'Admin Kurikulum',
                'username' => 'KurikulumWikrama',
                'password' => Hash::make('Wikrama2022'),
                'role' => User::ADMIN,
                'status' => true,
                'userable_id' => Teacher::factory(['name' => 'Admin Kurikulum'])->create()->id,
            ],
        ];
        // $users = [
        //     [
        //         'name' => 'Admin Kurikulum',
        //         'username' => 'KurikulumWikrama',
        //         'password' => Hash::make('Wikrama2022'),
        //         'role' => User::ADMIN,
        //         'status' => true,
        //         'userable_id' => Teacher::factory(['name' => 'Admin Kurikulum'])->create()->id,
        //     ],
        //     [
        //         'name' => 'Siswa 1',
        //         'username' => 'Siswa1',
        //         'password' => Hash::make('Wikrama2022'),
        //         'role' => User::STUDENT,
        //         'status' => true,
        //         'userable_id' => Student::factory(['name' => 'Siswa 1'])->create()->id,
        //     ],
        //     [
        //         'name' => 'Siswa 2',
        //         'username' => 'Siswa2',
        //         'password' => Hash::make('Wikrama2022'),
        //         'role' => User::STUDENT,
        //         'status' => true,
        //         'userable_id' => Student::factory(['name' => 'Siswa 2'])->create()->id,
        //     ],
        //     [
        //         'name' => 'Siswa 3',
        //         'username' => 'Siswa3',
        //         'password' => Hash::make('Wikrama2022'),
        //         'role' => User::STUDENT,
        //         'status' => true,
        //         'userable_id' => Student::factory(['name' => 'Siswa 3'])->create()->id,
        //     ],
        //     [
        //         'name' => 'Guru 1',
        //         'username' => 'Guru1',
        //         'password' => Hash::make('Wikrama2022'),
        //         'role' => User::TEACHER,
        //         'status' => true,
        //         'userable_id' => Teacher::factory(['name' => 'Guru 1'])->create()->id,
        //     ],
        //     [
        //         'name' => 'Guru 2',
        //         'username' => 'Guru2',
        //         'password' => Hash::make('Wikrama2022'),
        //         'role' => User::TEACHER,
        //         'status' => true,
        //         'userable_id' => Teacher::factory(['name' => 'Guru 2'])->create()->id,
        //     ],
        // ];

        foreach ($users as $user) {
            User::factory($user)->create();
        }

        // Subject::factory(['name' => 'Bahasa Indonesia', 'group' => Subject::MUATAN_A])->create();
        // Subject::factory(['name' => 'Bahasa Sunda', 'group' => Subject::MUATAN_B])->create();
        // Subject::factory(['name' => 'Pemrograman Dasar', 'group' => Subject::MUATAN_C])->create();

        // $reportPeriods = [
        //     [
        //         'title' => 'Tengah Semester Ganjil',
        //         'school_year' => '2021/2022',
        //         'type' => ReportPeriod::ODD
        //     ],
        //     [
        //         'title' => 'Semester Ganjil',
        //         'school_year' => '2021/2022',
        //         'type' => ReportPeriod::ODD
        //     ],
        //     [
        //         'title' => 'Tengah Semester Genap',
        //         'school_year' => '2021/2022',
        //         'type' => ReportPeriod::EVEN
        //     ],
        //     [
        //         'title' => 'Semester Genap',
        //         'school_year' => '2021/2022',
        //         'type' => ReportPeriod::EVEN
        //     ],
        // ];

        // foreach($reportPeriods as $reportPeriod){
        //     ReportPeriod::factory($reportPeriod)->create();
        // }

        $majors = [...config('constant.majors.wikrama_majors.K13'), ...config('constant.majors.wikrama_majors.K21')];

        $createdMajors = [];
        foreach($majors as $major) {
            $createdMajors[] = Major::factory($major)->create();
        }

        // $batches = [
        //     [
        //         'batch_name' => 'Angkatan 23',
        //         'entry_year' => '2019/2020'
        //     ],
        //     [
        //         'batch_name' => 'Angkatan 24',
        //         'entry_year' => '2020/2021'
        //     ],
        //     [
        //         'batch_name' => 'Angkatan 25',
        //         'entry_year' => '2021/2022'
        //     ],
        // ];

        // $createdBatches = [];
        // foreach ($batches as $batch) {
        //     $createdBatches[] = Batch::factory($batch)->create();
        // }

        // $academicCalendar = new AcademicCalendar;

        // foreach ($createdBatches as $batch) {
        //     $major = $faker->randomElement($createdMajors);

        //     $grade = $academicCalendar->gradeByAcademicYear($batch->entry_year, true);
        //     $randomNum = $faker->numberBetween(1, 5);

        //     $studentGroup = [
        //         'name' => "$major->abbreviation $grade-$randomNum",
        //         'batch_id' => $batch->id,
        //         'school_year' => $academicCalendar->currentAcademicYear(),
        //         'major_id' => $major->id,
        //     ];

        //     StudentGroup::factory($studentGroup)->create();
        // }

    }
}
