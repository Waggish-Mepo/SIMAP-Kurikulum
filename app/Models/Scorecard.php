<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function gradebook() {
        return $this->belongsTo(Gradebook::class);
    }

    public function scorecardComponents() {
        return $this->hasMany(ScorecardComponent::class)->orderBy('created_at');
    }
}
