<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attitude extends Model
{
    use HasFactory;

    public $incrementing = false;

    public const CHARACTER = "CHARACTER"; //nilai karakter
    public const COMPETENCE = "COMPETENCE"; //nilai sikap kompetensi
    public const PANCASILA = "PANCASILA"; //profil pemuda pancasila

    public function attitudePredicates() {
        return $this->hasMany(AttitudePredicate::class);
    }

    public function reportPeriod() {
        return $this->belongsTo(ReportPeriod::class);
    }

}
