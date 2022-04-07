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
}
