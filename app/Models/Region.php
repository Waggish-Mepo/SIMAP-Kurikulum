<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function students() {
        return $this->hasMany(Student::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
