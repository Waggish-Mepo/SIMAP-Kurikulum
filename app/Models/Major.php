<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function studentGroups() {
        return $this->hasMany(StudentGroup::class);
    }
}
