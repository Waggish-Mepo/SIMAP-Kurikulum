<?php

namespace App\Service\Database;

use App\Models\PredicateLetter;
use App\Service\Functions\AcademicCalendar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class PredicateLetterService {
    
    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $minScore = $filter['min_score'] ?? null;
        $maxScore = $filter['max_score'] ?? null;
        $letter = $filter['letter'] ?? null;
        $gradebookId = $filter['gradebook_id'] ?? null;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = PredicateLetter::orderBy('created_at', $orderBy);

        if ($minScore) {
            $query->where('min_score', $minScore);
        }

        if ($maxScore) {
            $query->where('max_score', $maxScore);
        }

        if ($letter) {
            $query->where('letter', $letter);
        }

        if ($gradebookId) {
            $query->where('gradebook_id', $gradebookId);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $predicateLetters = $query->paginate($perPage);

        return $predicateLetters;
    }

    public function detail($predicateLetterId) {
        $predicateLetter = PredicateLetter::findOrFail($predicateLetterId);

        return $predicateLetter->toArray();
    }

    public function create($payload) {

        $predicateLetter = new PredicateLetter;

        $predicateLetter->id = Uuid::uuid4()->toString();
        $predicateLetter = $this->fill($predicateLetter, $payload);
        $predicateLetter->save();

        return $predicateLetter->toArray();
    }

    public function update($predicateLetterId, $payload) {

        $predicateLetter = PredicateLetter::findOrFail($predicateLetterId);

        $predicateLetter = $this->fill($predicateLetter, $payload);
        $predicateLetter->save();

        return $predicateLetter->toArray();
    }

    private function fill(PredicateLetter $predicateLetter, array $payload) {
        foreach ($payload as $key => $value) {
            $predicateLetter->$key = $value;
        }

        $validate = Validator::make($predicateLetter->toArray(), [
            'min_score' => 'required|numeric',
            'max_score' => 'required|numeric',
            'letter' => 'required|string',
            'gradebook_id' => 'required|uuid',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $predicateLetter;
    }
}