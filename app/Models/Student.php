<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $incrementing = false;

     //gender
    public const LAKILAKI = "Laki-Laki";
    public const PEREMPUAN = "Perempuan";

    public function studentGroup() {
        return $this->belongsTo(StudentGroup::class);
    }

    public function absences() {
        return $this->hasMany(StudentAbsence::class);
    }

    public function scorecards() {
        return $this->hasMany(Scorecard::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'student_courses')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'userable_id');
    }

    public function reportbooks()
    {
        return $this->hasMany(Reportbook::class);
    }
}
