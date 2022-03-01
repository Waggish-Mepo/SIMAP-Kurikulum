<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public $incrementing = false;

    //curriculums
    public const K13_REVISI_2017_PERMENDIKBUD = 'K13 Revisi 2017 | Permendikbud No. 37 Tahun 2018';
    public const K13_REVISI_2017_PERDIRJEN = 'K13 Revisi 2017 SMK | Perdirjen Dikdasmen No. 464/D.D5/KR/2018';
    public const K13_PANDEMI_2020 = 'K13 Pandemi 2020 | SK Balitbang Kemendikbud No. 018/H/KR/2020';
    public const K21_SEKOLAH_PENGGERAK = 'K21 | Sekolah Penggerak';

    protected $casts = [
        'majors' => 'array',
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function studentCourses() {
        return $this->hasMany(StudentCourse::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_courses')
            ->withTimestamps();
    }
}
