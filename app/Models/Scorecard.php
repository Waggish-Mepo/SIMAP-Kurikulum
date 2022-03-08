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

    public function predicateLetter() {
        return $this->belongsTo(PredicateLetter::class);
    }

    public function components()
    {
        return $this->belongsToMany(GradebookComponent::class, 'scorecard_components')
            ->withPivot([
                'id',
                'scorecard_id',
                'gradebook_component_id',
                'knowledge_score',
                'skill_score',
                'general_score',
            ])
            ->orderBy('created_at', 'asc') // temporary
            ->withTimestamps();
    }

    public function scorecardComponents() {
        return $this->hasMany(ScorecardComponent::class)->orderBy('created_at');
    }
}
