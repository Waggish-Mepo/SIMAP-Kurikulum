<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttitudePredicate extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function studentAttitudes() {
        return $this->hasMany(StudentAttitude::class);
    }

    public function attitude() {
        return $this->belongsTo(Attitude::class);
    }
}
