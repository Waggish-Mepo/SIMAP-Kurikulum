<?php

namespace App\Service\Database;

use App\Models\Course;
use App\Models\Gradebook;
use App\Models\ReportPeriod;
use App\Models\Scorecard;
use App\Service\Functions\Gradebook as FunctionsGradebook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class ScorecardService{

    public function index($gradebookId, $filter = []) {

        Gradebook::findOrFail($gradebookId);

        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $students = $filter['student_ids'] ?? [];
        $withStudent = $filter['with_student'] ?? false;
        $withScorecardComponents = $filter['with_scorecard_components'] ?? false;
        $withLetter = $filter['with_letter'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Scorecard::where('gradebook_id', $gradebookId)->orderBy('created_at', $orderBy);

        if ($students) {
            $query->whereIn('student_id', $students);
        }

        if ($withStudent) {
            $query->with('student');
        }

        if ($withScorecardComponents) {
            $query->with('scorecardComponents');
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $courses = $query->paginate($perPage);

        return $courses;
    }

    public function detail($scorecardId) 
    {
        $scorecard = Scorecard::with('scorecardComponents')->findOrFail($scorecardId);

        return $scorecard->toArray();
    }

    public function bulkCreate($gradebookId, $payload)
    {
        $gradebook = Gradebook::with('course')->findOrFail($gradebookId);

        Validator::make($payload, [
            'students' => 'required|array',
            'students.*.id' => 'required|exists:students,id',
            'gradebook_id' => 'required|exists:gradebooks,id',
            'students.*.knowledge_score' => 'nullable|numeric',
            'students.*.skill_score' => 'nullable|numeric',
            'students.*.final_score' => 'nullable|numeric',
            'students.*.predicate' => 'nullable|string',
        ])->validate();

        $students = $payload['students'];
        $isK21 = $gradebook->course->curriculum === Course::K21_SEKOLAH_PENGGERAK;

        $scorecards = DB::transaction(function () use ($students, $gradebook, $isK21) {
            $data = collect([]);
            $result = collect([]);

            foreach ($students as $student) {
                $scorecard = new Scorecard;
                $scorecard->id = Uuid::uuid4()->toString();
                $scorecard->gradebook_id = $gradebook->id;
                $scorecard->student_id = $student['id'];
                $scorecard->knowledge_score = $student['knowledge_score'] ?? null;
                $scorecard->skill_score = $student['skill_score'] ?? null;
                $scorecard->final_score = $student['final_score'] ?? null;
                $scorecard->predicate = $student['predicate'];
                $data->push($scorecard->toArray());
                $result->push($scorecard);
            }

            Scorecard::insert($data->toArray());
            FunctionsGradebook::syncScorecardComponent($gradebook->id, $isK21);

            return [
                'scorecards' => $result,
            ];
        });

        return ['data' => $scorecards];
    }
}
