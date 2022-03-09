<?php

namespace App\Service\Database;

use App\Models\Gradebook;
use App\Models\Reportbook;
use App\Models\ReportPeriod;
use App\Models\Scorecard;
use App\Models\Student;
use App\Models\StudentAbsence;
use App\Service\Functions\Reportbook as FunctionsReportbook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class ReportbookService
{
    public function byStudent($reportPeriodId, $studentId)
    {
        ReportPeriod::findOrFail($reportPeriodId);

        $reportbooks = Reportbook::with([
            'student',
        ])->where('student_id', $studentId)
        ->where('report_period_id', $reportPeriodId)->get();

        DB::transaction(function () use ($reportbooks, $studentId, $reportPeriodId) {
            foreach ($reportbooks as $key => $reportbook) {
                $scorecard = Scorecard::with('gradebook', 'gradebook.course')->whereIn(
                    'id',
                    $reportbook->score_config,
                )->get();
                $reportbooks[$key]['scorecard'] = $scorecard;

                $absence = StudentAbsence::where('student_id', $studentId)
                            ->where('report_period_id', $reportPeriodId)
                            ->first();

                $reportbooks[$key]['absences'] = $absence;
            }
        });

        return $reportbooks->toArray();
    }

    public function init($reportPeriodId, $payload)
    {
        ReportPeriod::findOrFail($reportPeriodId);

        $reportCurriculum = $payload['curriculum'];
        $entryYear = $payload['entry_year'];
        $schoolYear = $payload['school_year'];

        $reportbooks = FunctionsReportbook::init(
            $reportPeriodId,
            $reportCurriculum,
            $entryYear,
            $schoolYear,
        );

        return $reportbooks->toArray();
    }

    public function update($reportbookId, $payload, $filter)
    {
        $reportbook = Reportbook::findOrFail($reportbookId);

        if ($filter['update_type'] === 'score_config') {
            $gradebooks = Gradebook::with('course')
                ->where('report_period_id', $reportbook->report_period_id)
                ->get();

            $scorecards = Scorecard::with(['gradebook', 'student'])
                ->where('student_id', $reportbook->student_id)
                ->whereIn('gradebook_id', $gradebooks->pluck('id'))
                ->get();

            $groupbyScorecard = $scorecards->pluck('id');

            $attributes = [
                'score_config' => $groupbyScorecard,
            ];

            $reportbook = $this->fill($reportbook, $attributes);

            $reportbook->save();

            return $reportbook->toArray();
        }

        $reportbook = $this->fill($reportbook, $payload['notes']);

        $reportbook->save();

        return $reportbook->toArray();
    }

    public function create($reportPeriodId, $studentId)
    {
        $student = Student::with('user')->findOrFail($studentId);

        $query = Reportbook::with('student');

        $reportbookExist = $query->where('report_period_id', $reportPeriodId)->first();

        $gradebooks = Gradebook::with('course')
            ->where('report_period_id', $reportPeriodId)
            ->get();

        $scorecard = Scorecard::with(['gradebook', 'student'])
            ->where('student_id', $student->id)
            ->whereIn('gradebook_id', $gradebooks->pluck('id'))
            ->get();

        $scorecardIds = $scorecard->pluck('id');

        $absence = StudentAbsence::where('student_id', $studentId)
                    ->where('report_period_id', $reportPeriodId)
                    ->first()->id;

        $reportbook = new Reportbook;
        $reportbook->id = Uuid::uuid4()->toString();
        $reportbook->student_id = $studentId;
        $reportbook->report_period_id = $reportPeriodId;
        $reportbook->score_config = $scorecardIds;
        $reportbook->student_absence_id = $absence;
        $reportbook->notes = null;
        $reportbook->report_curriculum = $reportbookExist->report_curriculum;

        $reportbook->save();

        return $reportbook->toArray();
    }

    private function fill(Reportbook $reportbook, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $reportbook->$key = $value;
        }

        $reportbookArray = $reportbook->toArray();
        $reportbookArray['notes'] = (array) $reportbook['notes'];
        $reportbookArray['score_config'] = $reportbook['score_config'];

        Validator::make($reportbookArray, [
            'notes' => 'nullable|string',
            'score_config' => ['nullable', 'array'],
        ])->validate();

        return $reportbook;
    }
}