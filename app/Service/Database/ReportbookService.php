<?php

namespace App\Service\Database;

use App\Models\Gradebook;
use App\Models\Reportbook;
use App\Models\ReportPeriod;
use App\Models\Scorecard;
use App\Models\Student;
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

        DB::transaction(function () use ($reportbooks) {
            foreach ($reportbooks as $key => $reportbook) {
                $scorecard = Scorecard::with('gradebook', 'gradebook.course')->whereIn(
                    'id',
                    $reportbook->score_config,
                )->get();
                $reportbooks[$key]['scorecard'] = $scorecard;

                // $queryIll = Presence::whereIn('id', $reportbook->absence_config->ill);
                // $queryUnknown = Presence::whereIn('id', $reportbook->absence_config->unknown);
                // $queryOnLeave = Presence::whereIn('id', $reportbook->absence_config->on_leave);

                // if ($checkTime !== null) {
                //     $from = Carbon::parse($checkTime['start_time'])->format('Y-m-d H:i:s');
                //     $to = Carbon::parse($checkTime['end_time'])->format('Y-m-d H:i:s');

                //     $queryIll->whereBetween('check_time', [$from, $to]);
                //     $queryUnknown->whereBetween('check_time', [$from, $to]);
                //     $queryOnLeave->whereBetween('check_time', [$from, $to]);
                // }

                // $ill = $queryIll->get();
                // $unknown = $queryUnknown->get();
                // $onLeave = $queryOnLeave->get();

                // $absences = [];

                // $absences['ill'] = $ill->pluck('check_time') ?? [];
                // $absences['unknown'] = $unknown->pluck('check_time') ?? [];
                // $absences['on_leave'] = $onLeave->pluck('check_time') ?? [];

                // $reportbooks[$key]['absences'] = $absences;
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

        // if ($this->request->input('sync') === 'absence_config') {
        //     $student = Student::with(['user'])->findOrFail($reportbook->student_id);


        //     $presenceSick = Presence::where('user_id', $student->user->id)
        //         ->where('type', Presence::ABSENT)
        //         ->where('status', Presence::SICK)
        //         ->where('non_daily', 0)
        //         ->get()
        //         ->pluck('id');

        //     $presenceLeave = Presence::where('user_id', $student->user->id)
        //         ->where('type', Presence::ABSENT)
        //         ->where('status', Presence::LEAVE)
        //         ->where('non_daily', 0)
        //         ->get()
        //         ->pluck('id');

        //     $presenceUnknown = Presence::where('user_id', $student->user->id)
        //         ->where('type', Presence::ABSENT)
        //         ->where('status', Presence::UNKNOWN)
        //         ->where('non_daily', 0)
        //         ->get()
        //         ->pluck('id');

        //     $absence = [];

        //     $absence['ill'] = $presenceSick ?? [];
        //     $absence['on_leave'] = $presenceLeave ?? [];
        //     $absence['unknown'] = $presenceUnknown ?? [];

        //     $reportbook->absence_config = $absence;

        //     $reportbook->save();

        //     return $this->respondWithResource($reportbook);
        // }

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

        // $presenceSick = Presence::where('user_id', $student->user->id)
        //     ->where('type', Presence::ABSENT)
        //     ->where('status', Presence::SICK)
        //     ->get()
        //     ->pluck('id');

        // $presenceLeave = Presence::where('user_id', $student->user->id)
        //     ->where('type', Presence::ABSENT)
        //     ->where('status', Presence::LEAVE)
        //     ->get()
        //     ->pluck('id');

        // $presenceUnknown = Presence::where('user_id', $student->user->id)
        //     ->where('type', Presence::ABSENT)
        //     ->where('status', Presence::UNKNOWN)
        //     ->get()
        //     ->pluck('id');


        // $abcence = [];

        // $abcence['ill'] = $presenceSick ?? [];
        // $abcence['on_leave'] = $presenceLeave ?? [];
        // $abcence['unknown'] = $presenceUnknown ?? [];

        $reportbook = new Reportbook;
        $reportbook->id = Uuid::uuid4()->toString();
        $reportbook->student_id = $studentId;
        $reportbook->report_period_id = $reportPeriodId;
        $reportbook->score_config = $scorecardIds;
        // $reportbook->absence_config = $abcence;
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
