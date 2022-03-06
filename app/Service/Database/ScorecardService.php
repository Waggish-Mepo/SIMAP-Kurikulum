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
    public function index($reportPeriodId, $gradebookId)
    {
        ReportPeriod::findOrFail($reportPeriodId);
        Gradebook::findOrFail($gradebookId);

        $scorecards = Scorecard::with([
            'knowledgeScoreLetter',
            'skillScoreLetter',
            'finalScoreLetter',
        ])->where('gradebook_id', $gradebookId)->orderBy('created_at', 'asc')->paginate($this->perPage());

        return $scorecards;
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
