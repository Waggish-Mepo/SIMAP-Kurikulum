<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradebookComponent extends Model
{
    use HasFactory;

    public $incrementing = false;

    public function gradebook() {
        return $this->belongsTo(Gradebook::class);
    }

    public function scorecardComponents() {
        return $this->hasMany(ScorecardComponent::class);
    }
}
