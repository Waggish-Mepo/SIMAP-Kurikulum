<?php

namespace App\Service\Database;

use App\Models\Attitude;
use App\Models\AttitudePredicate;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AttitudePredicateService {

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $attitudeId = $filter['attitude_id'] ?? null;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = AttitudePredicate::orderBy('order', $orderBy);

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

    public function delete($attitudePredicateId) {

        $attitudePredicate = AttitudePredicate::findOrFail($attitudePredicateId);

        return $attitudePredicate->toArray();
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
