<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public $incrementing = false;

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
