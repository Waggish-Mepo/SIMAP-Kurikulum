<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttitude extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function attitudePredicate() {
        return $this->belongsTo(AttitudePredicate::class);
    }
}
