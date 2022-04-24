<?php

namespace App\Service\Database;

use App\Models\ReportPeriod;
use App\Service\Functions\AcademicCalendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class ReportPeriodService{
    public function index($filter = []) {

        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $schoolYear = $filter['school_year'] ?? null;
        $title = $filter['title'] ?? null;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = ReportPeriod::orderBy('created_at', $orderBy);

        if ($schoolYear) {
            $query->where('school_year', $schoolYear);
        }

        if ($title) {
            $query->where('title', 'LIKE', '%' . $title . '%');
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $reports = $query->paginate($perPage);

        return $reports;
    }

    public function detail($reportPeriodId, $withAttitude = false) {

        if($withAttitude) {
            return ReportPeriod::with('attitudes')->findOrFail($reportPeriodId);
        }

        $report = ReportPeriod::findOrFail($reportPeriodId);

        return $report->toArray();
    }

    public function schoolYears() {
        return config('constant.common.school_years');
    }

    public function create($payload) {

        $reportPeriod = new ReportPeriod;

        $academicCalendarService = new AcademicCalendar;

        $reportPeriod->id = Uuid::uuid4()->toString();
        $reportPeriod->school_year = $academicCalendarService->currentAcademicYear();
        $reportPeriod = $this->fill($reportPeriod, $payload);
        $reportPeriod->save();

        return $reportPeriod->toArray();
    }

    public function update($reportPeriodId, $payload) {

        $reportPeriod = ReportPeriod::findOrFail($reportPeriodId);

        $reportPeriod = $this->fill($reportPeriod, $payload);
        $reportPeriod->save();

        return $reportPeriod->toArray();
    }

    public function delete($reportPeriodId) {

        $reportPeriod = ReportPeriod::findOrFail($reportPeriodId);

        $attitudes = $reportPeriod->attitudes();
        $reportbooks = $reportPeriod->reportbooks();
        $gradebooks = $reportPeriod->gradebooks();
        $absences = $reportPeriod->absences();

        DB::transaction(function () use ($reportPeriod, $attitudes, $reportbooks, $gradebooks, $absences) {

            foreach ($attitudes as $attitude) {
                $attitudePredicates = $attitude->attitudePredicates;

                foreach ($attitudePredicates as $attitudePredicate) {
                    $attitudePredicate->studentAttitudes()->delete();
                    $attitudePredicate->delete();
                }
            }

            foreach ($gradebooks as $gradebook) {
                $gradebookComponents = $gradebook->components();

                foreach($gradebookComponents as $gradebookComponent) {
                    $gradebookComponent->scorecardComponents()->delete();
                }

                $gradebookComponents->delete();
                $gradebook->scorecards()->delete();
                $gradebook->predicateLetters()->delete();
            }

            $attitudes->delete();
            $reportbooks->delete();
            $absences->delete();
            $gradebooks->delete();
            $reportPeriod->delete();
        });

        return 'ok';
    }

    private function fill(ReportPeriod $reportPeriod, array $payload) {
        foreach ($payload as $key => $value) {
            $reportPeriod->$key = $value;
        }

        $validate = Validator::make($reportPeriod->toArray(), [
            'title' => 'required|string',
            'school_year' => ['required', Rule::in(config('constant.common.school_years'))],
            'start_date' => 'nullable',
            'end_date' => 'nullable',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $reportPeriod;
    }

}
