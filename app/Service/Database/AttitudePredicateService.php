<?php

namespace App\Service\Database;

use App\Models\Attitude;
use App\Models\AttitudePredicate;
use App\Models\Reportbook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AttitudePredicateService {

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $attitudeId = $filter['attitude_id'] ?? null;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = AttitudePredicate::orderBy('created_at', $orderBy);

        if ($attitudeId) {
            $query->where('attitude_id', $attitudeId);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $attitudePredicates = $query->paginate($perPage);

        return $attitudePredicates;
    }

    public function detail($attitudePredicateId) {
        $attitudePredicate = AttitudePredicate::findOrFail($attitudePredicateId);

        return $attitudePredicate->toArray();
    }

    public function create($payload) {

        $attitudePredicate = new AttitudePredicate;

        Attitude::findOrFail($payload['attitude_id']);

        $attitudePredicate->id = Uuid::uuid4()->toString();
        $attitudePredicate->attitude_id = $payload['attitude_id'];
        $attitudePredicate = $this->fill($attitudePredicate, $payload);
        $attitudePredicate->save();

        return $attitudePredicate->toArray();
    }

    public function update($attitudePredicateId, $payload) {

        $attitudePredicate = AttitudePredicate::findOrFail($attitudePredicateId);

        $attitudePredicate = $this->fill($attitudePredicate, $payload);
        $attitudePredicate->save();

        return $attitudePredicate->toArray();
    }

    public function delete($reportPeriodId, $attitudePredicateId) {

        $reportbookService = new ReportbookService;;

        $attitudePredicate = AttitudePredicate::findOrFail($attitudePredicateId);

        $studentAttitudes = $attitudePredicate->studentAttitudes();

        $studentIds = $studentAttitudes->pluck('student_id');

        $reportbookIds = Reportbook::where('report_period_id', $reportPeriodId)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->pluck('id');

        DB::transaction(function () use ($attitudePredicate, $reportbookService, $reportbookIds) {
            $attitudePredicate->studentAttitudes()->delete();
            $attitudePredicate->delete();

            foreach ($reportbookIds as $reportbookId) {
                $reportbookService->update($reportbookId, [], ['update_type' => 'attitude_config']);
            }
        });

        return 'ok';
    }

    private function fill(AttitudePredicate $attitudePredicate, array $payload) {
        foreach ($payload as $key => $value) {
            $attitudePredicate->$key = $value;
        }

        $validate = Validator::make($attitudePredicate->toArray(), [
            'description' => 'required|string',
            'predicate' => 'required|string',
            'attitude_id' => 'required|uuid',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $attitudePredicate;
    }
}
