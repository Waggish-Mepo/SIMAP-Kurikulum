<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function major() {
        return $this->belongsTo(Major::class);
    }

    public function studentGroups() {
        return $this->hasMany(StudentGroup::class);
    }
}
