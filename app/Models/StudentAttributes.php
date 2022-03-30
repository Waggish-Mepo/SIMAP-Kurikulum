<?php

namespace App\Models;

use App\Models\AttitudePredicate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttributes extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function attitudePredicate() {
        return $this->belongsTo(AttitudePredicate::class);
    }
}
