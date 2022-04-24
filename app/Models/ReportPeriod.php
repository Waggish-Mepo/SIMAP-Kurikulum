<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPeriod extends Model
{
    use HasFactory;

    public const ODD = 'ODD';
    public const EVEN = 'EVEN';

    public $incrementing = false;

    public function attitudes() {
        return $this->hasMany(Attitude::class);
    }

    public function reportbooks() {
        return $this->hasMany(Reportbook::class);
    }

    public function gradebooks() {
        return $this->hasMany(Gradebook::class);
    }

    public function absences() {
        return $this->hasMany(StudentAbsence::class);
    }
}
