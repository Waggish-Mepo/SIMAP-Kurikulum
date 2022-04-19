<?php

namespace App\Service\Database;

use App\Models\Region;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class RegionService {

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $name = $filter['name'] ?? null;
        $teacherId = $filter['teacher_id'] ?? null;
        $teacher = $filter['with_teacher'] ?? false;
        $students = $filter['with_students'] ?? false;
        $perPage = $filter['page'] ?? 20;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Region::orderBy('created_at', $orderBy);

        if ($teacher) {
            $query->with('teacher');
        }

        if ($students) {
            $query->with('students');
        }

        if ($name) {
            $query->where('name','LIKE', '%'. $name . '%');
        }

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $regions = $query->paginate($perPage);

        return $regions;
    }

    public function detail($regionId, $withTeacher = false) {
        if ($withTeacher) {
            $region = Region::with('teacher')->find($regionId);

            return $region->toArray() ?? [];
        }

        $region = Region::find($regionId);

        return $region->toArray();
    }

    public function create($payload) {

        $region = new Region;

        $region->id = Uuid::uuid4()->toString();
        $region = $this->fill($region, $payload);
        $region->save();

        return $region->toArray();
    }

    public function update($regionId, $payload) {

        $region = Region::findOrFail($regionId);

        $region = $this->fill($region, $payload);
        $region->save();

        return $region->toArray();
    }


    private function fill(Region $region, array $payload) {
        foreach ($payload as $key => $value) {
            $region->$key = $value;
        }

        $validate = Validator::make($region->toArray(), [
            'name' => 'required|string',
            // 'teacher_id' => 'uuid',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $region;
    }

}
