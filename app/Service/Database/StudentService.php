<?php

namespace App\Service\Database;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Service\Functions\Username;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class StudentService {
    public function index($filter = [])
    {
        $orderBy = $filter['order_by'] ?? 'nis';
        $orderType = $filter['order_type'] ?? 'ASC';
        $anotherOrderBy = $filter['order'] ?? false;
        $per_page = $filter['per_page'] ?? 99;
        $name = $filter['name'] ?? null;
        $nis = $filter['nis'] ?? null;
        $studentGroup = $filter['student_group_id'] ?? null;
        $studentGroupRelation = $filter['with_student_group'] ?? false;
        $region = $filter['region_id'] ?? null;
        $withoutRegion = $filter['without_region'] ?? false;
        $absences = $filter['with_student_absence'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Student::orderBy($orderBy, $orderType);

        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        if ($nis) {
            $query->where('nis', 'LIKE', '%' . $nis . '%');
        }

        if ($region) {
            $query->where('region_id', $region);
        }

        if ($withoutRegion) {
            $query->where('region_id', NULL);
        }

        if ($anotherOrderBy) {
            $query->orderBy('student_group_id', 'ASC');
        }

        if ($studentGroup) {
            $query->where('student_group_id', $studentGroup);
        }

        if ($studentGroupRelation) {
            $query->with('studentGroup');
        }

        if ($absences) {
            $query->with('absences');
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $users = $query->paginate($per_page);

        return $users;
    }

    public function detail($studentId)
    {
        $student = Student::findOrFail($studentId);

        return $student->toArray();
    }

    public function next($nis, $sg, $id)
    {
        $student = Student::where([
            ['nis', '>=', $nis],
            ['student_group_id', $sg],
            ['id', '!=', $id]
        ])->oldest()->first();

        return $student;
    }

    public function prev($nis, $sg, $id)
    {
        $student = Student::where([
            ['nis', '<=', $nis],
            ['student_group_id', $sg],
            ['id', '!=', $id]
        ])->oldest()->first();

        return $student;
    }

    public function bulkDetail($studentIds)
    {
        $query = Student::whereIn('id', $studentIds);

        $users = $query->simplePaginate(100);

        return $users->toArray();
    }

    public function accountStatistics() {

        $teacherCount = Teacher::all()->count();
        $studentCount = Student::all()->count();
        $adminCount = User::where('role', User::ADMIN)->count();

        $statistics = [
            'teachers' => $teacherCount,
            'students' => $studentCount,
            'admin' => $adminCount
        ];

        return $statistics;
    }

    public function create($payload)
    {
        $student = new Student;
        $student->id = Uuid::uuid4()->toString();
        $student = $this->fill($student, $payload);
        $student->save();

        $username = Username::generateUsername($student->name);
        $user = new User;
        $user->id = Uuid::uuid4()->toString();
        $user->name = $student->name;
        $user->username = $username;
        $user->password = Hash::make($username);
        $user->role = User::STUDENT;
        $user->status = true;
        $user->userable_id = $student->id;
        $user->save();


        return $student;
    }

    public function update($studentId, $payload)
    {
        $student = Student::findOrFail($studentId);
        $student = $this->fill($student, $payload);
        $student->save();

        return $student;
    }

    public function delete($studentId)
    {
        $student = Student::findOrFail($studentId);

        DB::transaction(function () use ($student) {
            $scorecards = $student->scorecards();
            if(count($scorecards->get())) {
                $scorecards->scorecardComponents()->delete();
            }
            $scorecards->delete();
            $student->absences()->delete();
            $student->courses()->detach();
            $student->user()->delete();
            $student->reportbooks()->delete();
            $student->delete();
        });

        return 'ok';
    }

    private function fill(Student $student, array $attributes)
    {

        foreach ($attributes as $key => $value) {
            $student->$key = $value;
        }

        $validate = Validator::make($student->toArray(), [
            'name' => 'required|string',
            'nis' => 'required',
            'nisn' => 'nullable',
            'jk' => ['nullable', Rule::in(config('constant.student.gender'))],
            'student_group_id' => 'nullable',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $student;
    }
}
