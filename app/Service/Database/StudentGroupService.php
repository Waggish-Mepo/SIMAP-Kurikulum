<?php

namespace App\Service\Database;

use App\Models\Batch;
use App\Models\StudentGroup;
use App\Service\Functions\AcademicCalendar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class StudentGroupService {

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $schoolYear = $filter['school_year'] ?? null;
        $perPage = $filter['page'] ?? 20;
        $withBatch = $filter['with_batch'] ?? false;
        $withMajor = $filter['with_major'] ?? false;
        $withStudent = $filter['with_student'] ?? false;
        $groupByMajor = $filter['group_by_major'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = StudentGroup::orderBy('created_at', $orderBy);

        if ($schoolYear) {
            $query->where('school_year', $schoolYear);
        }

        if ($withBatch) {
            $query->with('batch');
        }

        if ($withStudent) {
            $query->with('students');
        }

        if ($withMajor) {
            $query->with('major');

            if ($groupByMajor) {
                $studentGroups = collect($query->get())->mapToGroups(function($item, $key) {
                    return [$item['major']['id'] => $item];
                });

                return $studentGroups
            }

            if ($withoutPagination) {
                return $query->get()->toArray();
            }

            return $query->paginate($perPage);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $studentGroups = $query->paginate($perPage);

        return $studentGroups;
    }

    public function detail($studentGroupId) {
        $studentGroup = StudentGroup::findOrFail($studentGroupId);

        return $studentGroup->toArray();
    }

    public function create($payload) {

        $studentGroup = new StudentGroup;

        $studentGroup->id = Uuid::uuid4()->toString();
        $studentGroup = $this->fill($studentGroup, $payload);
        $studentGroup->save();

        return $studentGroup->toArray();
    }

    public function update($studentGroupId, $payload) {

        $studentGroup = StudentGroup::findOrFail($studentGroupId);

        $studentGroup = $this->fill($studentGroup, $payload);
        $studentGroup->save();

        return $studentGroup->toArray();
    }


    private function fill(StudentGroup $studentGroup, array $payload) {
        foreach ($payload as $key => $value) {
            $studentGroup->$key = $value;
        }

        $validate = Validator::make($studentGroup->toArray(), [
            'name' => 'required|string',
            'school_year' => ['required', Rule::in(config('constant.common.school_years'))],
            'batch_id' => 'required',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $studentGroup;
    }
}
