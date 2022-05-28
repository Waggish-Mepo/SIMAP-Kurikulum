<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    public $incrementing = false;

    //gender
    public const LAKILAKI = "Laki-Laki";
    public const PEREMPUAN = "Perempuan";

    public function subjects() {
        return $this->belongsToMany(Subject::class, 'subject_teachers');
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'userable_id');
    }
}
