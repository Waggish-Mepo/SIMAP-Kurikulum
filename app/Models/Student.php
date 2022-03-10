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
}
