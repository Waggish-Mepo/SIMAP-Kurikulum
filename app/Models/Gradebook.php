<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gradebook extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $casts = [
        'components' => 'array',
        'weights' => 'object',
        'scorebar' => 'decimal:2',
    ];

    // Weight Type or Components
    public const KNOWLEDGE = 'KNOWLEDGE';
    public const SKILL = 'SKILL';
    public const GENERAL = 'GENERAL';
  
    public function predicateLetters() {
        return $this->belongsTo(PredicateLetter::class);

    public function components() {
        return $this->hasMany(GradebookComponent::class);

    }
}
