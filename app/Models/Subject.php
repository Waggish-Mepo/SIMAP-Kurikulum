<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public $incrementing = false;

    public const MUATAN_A = 'Muatan A';
    public const MUATAN_B = 'Muatan B';
    public const MUATAN_C = 'Muatan C';

    public function subjectTeacher() {
        return $this->hasOne(SubjectTeacher::class);
    }
}
