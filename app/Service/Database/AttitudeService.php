<?php

namespace App\Service\Database;

use App\Models\Attitude;
use App\Models\Reportbook;
use App\Models\ReportPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class AttitudeService {

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $type = $filter['type'] ?? null;
        $reportPeriodId = $filter['report_period_id'] ?? null;
        $withPredicates = $filter['with_predicates'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Attitude::orderBy('order', $orderBy);

        if ($withPredicates) {
            $query->with('attitudePredicates');
        }

        if ($reportPeriodId) {
            $query->where('report_period_id', $reportPeriodId);
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

    public function attitudes()
    {
        return config('constant.report_period.attitudes');
    }

    public function detail($attitudeId) {
        $attitude = Attitude::findOrFail($attitudeId);

        return $attitude->toArray();
    }

    public function detailByOrder($order, $reportPeriod) {
        $attitude = Attitude::where([
            ['order', $order],
            ['report_period_id', $reportPeriod]
        ])->first();

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

    public function delete($reportPeriodId, $attitudeId) {
        $reportbookService = new ReportbookService;

        $attitude = Attitude::with(['attitudePredicates', 'attitudePredicates.studentAttitudes'])->findOrFail($attitudeId);

        $studentIds = $attitude->attitudePredicates->pluck('studentAttitudes')->flatten(1)->pluck('student_id');

        $reportbookIds = Reportbook::where('report_period_id', $reportPeriodId)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->pluck('id');

        DB::transaction(function () use ($attitude, $reportbookService, $reportbookIds, $reportPeriodId) {

            $attitudePredicates = $attitude->attitudePredicates;
            $attitudeType = $attitude->type;

            foreach ($attitudePredicates as $attitudePredicate) {
                $attitudePredicate->studentAttitudes()->delete();
                $attitudePredicate->delete();
            }

            foreach ($reportbookIds as $reportbookId) {
                $reportbookService->update($reportbookId, [], ['update_type' => 'attitude_config']);
            }

            $attitude->delete();

            $this->reorder($reportPeriodId, $attitudeType);
        });

        return 'ok';
    }

    private function reorder($reportPeriodId, $type) {
        $attitudes = Attitude::orderBy('order', 'asc')
            ->where('report_period_id', $reportPeriodId)
            ->where('type', $type)
            ->get();

        $startOrder = 1;

        foreach($attitudes as $attitude) {
            $attitude->order = $startOrder;
            $attitude->save();
            $startOrder++;
        }
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
