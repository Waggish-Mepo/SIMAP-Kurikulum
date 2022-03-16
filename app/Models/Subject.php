<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public $incrementing = false;

    public const MUATAN_A = "A (Muatan Nasional)";
    public const MUATAN_B = "B (Muatan Kewilayahan)";
    public const MUATAN_C = "C (Muatan Produktif)";
    public const MUATAN_C1 = "C1 (Dasar Bidang Keahlian)";
    public const MUATAN_C2 = "C2 (Dasar Program Keahlian)";
    public const MUATAN_C3 = "C3 (Dasar Kompetensi Keahlian)";

    public function subjectTeachers() {
        return $this->hasMany(SubjectTeacher::class);
    }

    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function teachers() {
        return $this->belongsToMany(Teacher::class, 'subject_teachers');
    }
}
