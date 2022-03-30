<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attitude extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function reportPeriod() {
        return $this->belongsTo(ReportPeriod::class);
    }

}
