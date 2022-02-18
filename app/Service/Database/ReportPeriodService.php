<?php

namespace App\Service\Database;

use App\Models\Subject;
use App\Models\ReportPeriod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class ReportPeriodService{
    public function index($filter = []) {

        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $schoolYear = $filter['school_year'] ?? null;

        $query = ReportPeriod::orderBy('created_at', $orderBy);

        if ($schoolYear) {
            $query->where('school_year', $schoolYear);
        }

        $subjects = $query->paginate($perPage);

        return $subjects;
    }

    public function detail($reportPeriodId) {

        $subject = ReportPeriod::findOrFail($reportPeriodId);

        return $subject->toArray();
    }

    public function create($payload) {

        $reportPeriod = new ReportPeriod;

        $reportPeriod->id = Uuid::uuid4()->toString();
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
