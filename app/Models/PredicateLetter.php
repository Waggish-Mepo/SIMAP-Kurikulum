<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredicateLetter extends Model
{
    use HasFactory;

    public $incrementing = false;
    
    public function predicateLetters() {
        return $this->belongsTo(PredicateLetter::class);
    }
}
