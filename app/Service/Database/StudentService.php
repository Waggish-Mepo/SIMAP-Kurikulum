<?php

namespace App\Service\Database;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class StudentService {
    public function index($filter = [])
    {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $anotherOrderBy = $filter['order'] ?? false;
        $per_page = $filter['per_page'] ?? 99;
        $name = $filter['name'] ?? null;
        $studentGroup = $filter['student_group_id'] ?? null;
        $studentGroupRelation = $filter['with_student_group'] ?? false;
        $absences = $filter['with_student_absence'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Student::orderBy('nis', $orderBy);

        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
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

        $teacherCount = User::where('role', User::TEACHER)->count();
        $studentCount = User::where('role', User::STUDENT)->count();
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

        return $student;
    }

    public function update($studentId, $payload)
    {
        $student = Student::findOrFail($studentId);
        $student = $this->fill($student, $payload);
        $student->save();

        return $student;
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
