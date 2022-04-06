<?php

namespace App\Service\Database;

use App\Models\Attitude;
use App\Models\Course;
use App\Models\Gradebook;
use App\Models\PredicateLetter;
use App\Models\ReportPeriod;
use App\Service\Functions\AcademicCalendar;
use App\Service\Functions\Gradebook as FunctionsGradebook;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class AttitudeService {

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $type = $filter['type'] ?? null;
        $withPredicates = $filter['with_predicates'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Attitude::orderBy('order', $orderBy);

        if ($withPredicates) {
            $query->with('attitudePredicates');
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $attitudes = $query->paginate($perPage);

        return $attitudes;
    }

    public function detail($attitudeId) {
        $attitude = Attitude::findOrFail($attitudeId);

        return $attitude->toArray();
    }

    public function create($payload) {

        $attitude = new Attitude;

        ReportPeriod::findOrFail($payload['report_period_id']);

        $attitude->id = Uuid::uuid4()->toString();
        $attitude->report_period_id = $payload['report_period_id'];
        $attitude = $this->fill($attitude, $payload);
        $attitude->save();

        return $attitude->toArray();
    }

    public function update($attitudeId, $payload) {

        $attitude = Attitude::findOrFail($attitudeId);

        $attitude = $this->fill($attitude, $payload);
        $attitude->save();

        return $attitude->toArray();
    }

    public function delete($attitudeId) {

        $attitude = Attitude::findOrFail($attitudeId);

        return $attitude->toArray();
    }

    private function fill(Attitude $attitude, array $payload) {
        foreach ($payload as $key => $value) {
            $attitude->$key = $value;
        }

        $validate = Validator::make($attitude->toArray(), [
            'name' => 'required|string',
            'order' => 'required|numeric',
            'type' => ['required', Rule::in(config('constant.report_period.attitudes'))],
            'report_period_id' => 'required|uuid',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $attitude;
    }
}
