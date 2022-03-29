<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attitude extends Model
{
    use HasFactory;

    public $incrementing = false;

    // public function attitude() {
    //     return $this->belongsTo(Attitude::class);
    // }

    public function reportPeriods() {
        return $this->hasMany(ReportPeriod::class);
    }

}
