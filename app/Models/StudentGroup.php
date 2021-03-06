<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function batch() {
        return $this->belongsTo(Batch::class);
    }

    public function major() {
        return $this->belongsTo(Major::class);
    }

    public function students() {
        return $this->hasMany(Student::class);
    }
}
