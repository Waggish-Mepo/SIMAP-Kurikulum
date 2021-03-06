<?php

namespace App\Service\Functions;

use App\Models\Batch;
use App\Models\Gradebook as ModelsGradebook;
use App\Models\Reportbook as ModelsReportbook;
use App\Models\ReportPeriod;
use App\Models\Scorecard;
use App\Models\StudentAbsence;
use App\Models\StudentAttitude;
use App\Models\StudentCourse;
use App\Models\StudentGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Reportbook
{
    public static function init($reportPeriodId, $curriculum, $entryYear, $schoolYear)
    {
        // check Report Period
        ReportPeriod::findOrFail($reportPeriodId);

        // Get Gradebook by report period and by it reportbook status
        $gradebooks = ModelsGradebook::with('course')
            ->where('report_period_id', $reportPeriodId)
            ->get();

        $courses = $gradebooks->pluck('course_id');

        $batchesId = Batch::where('entry_year', $entryYear)->pluck('id');

        $studentGroupIds = StudentGroup::whereIn('batch_id', $batchesId)
            ->where('school_year', $schoolYear)
            ->pluck('id');

        $query = StudentCourse::with(['student'])
            ->whereIn('course_id', $courses);

        $query->whereHas('student', function ($q) use ($studentGroupIds) {
            $q->whereIn('students.student_group_id', $studentGroupIds);
        });

        $studentCourse = $query->get()
                            ->mapToGroups(fn ($item) => [$item['student_id'] => $item['student_id']])
                            ->toArray();

        $studentIds = array_keys($studentCourse);

        $scorecards = Scorecard::with(['gradebook', 'student'])
            ->whereIn('student_id', $studentIds)
            ->whereIn('gradebook_id', $gradebooks->pluck('id'))
            ->get();

        $groupbyScorecard = $scorecards->mapToGroups(fn ($item) => [$item['student_id'] => $item['id']]);

        $studentAbsenceIds = StudentAbsence::whereIn('student_id', $studentIds)
            ->where('report_period_id', $reportPeriodId)
            ->get()
            ->mapToGroups(fn ($item) => [$item['student_id'] => $item['id']]);

        $studentAttitudes = StudentAttitude::whereIn('student_id', $studentIds)
            ->get()
            ->mapToGroups(fn ($item) => [$item['student_id'] => $item['id']]);

        $reportbookExists = ModelsReportbook::where('report_period_id', $reportPeriodId)
            ->get()
            ->mapWithKeys(fn ($item) => [$reportPeriodId.$item->student_id => $item->id]);

        // Begin Transaction on DB, for data safety when error
        return DB::transaction(function () use (
            $groupbyScorecard,
            $curriculum,
            $reportPeriodId,
            $reportbookExists,
            $studentIds,
            $studentAbsenceIds,
            $studentAttitudes
        ) {
            $reportbooks = collect([]);

            foreach ($studentIds as $studentId) {
                $isExist = isset(
                    $reportbookExists[$reportPeriodId.$studentId],
                );

                $scorecard = $groupbyScorecard[$studentId] ?? [];
                $studentAbsenceId = $studentAbsenceIds[$studentId] ?? null;
                $studentAttitude = $studentAttitudes[$studentId] ?? [];

                if (!$isExist) {
                    $reportbook = new ModelsReportbook;
                    $reportbook->id = Uuid::uuid4()->toString();
                    $reportbook->student_id = $studentId;
                    $reportbook->report_period_id = $reportPeriodId;
                    $reportbook->student_absence_id = $studentAbsenceId;
                    $reportbook->score_config = $scorecard;
                    $reportbook->attitude_config = $studentAttitude;
                    $reportbook->curriculum = $curriculum;
                    $reportbook->notes = null;
                    $reportbook->attitude_notes = null;
                    $reportbook->save();
                    $reportbooks->push($reportbook);

                    continue;
                }

                $reportbookId = $reportbookExists[$reportPeriodId.$studentId];
                $reportbook = ModelsReportbook::findOrFail($reportbookId);
                $reportbook->score_config = $scorecard;
                $reportbook->attitude_config = $studentAttitude;
                $reportbook->curriculum = $curriculum;
                $reportbook->save();
                $reportbooks->push($reportbook);
            }

            return $reportbooks;
        });
    }

    public static function determineSemester($academicYear, $type) {
        $currDate = Carbon::now();
        $nextDate = Carbon::now();
        $nextDate->setDay(19);
        $nextDate->setMonth(6);

        $yearNow = Carbon::now()->year;
        if ($currDate <= $nextDate) {
            $yearNow -= 1;
        }

        if ($type === ReportPeriod::EVEN) {
            $grade = [
                $yearNow . '/' . ($yearNow + 1) => '2',
                ($yearNow - 1) . '/' . $yearNow => '4',
                ($yearNow - 2) . '/' . ($yearNow - 1) => '6',
            ];
        } else {
            $grade = [
                $yearNow . '/' . ($yearNow + 1) => '1',
                ($yearNow - 1) . '/' . $yearNow => '3',
                ($yearNow - 2) . '/' . ($yearNow - 1) => '5',
            ];
        }

        return $grade[$academicYear];
    }
}
