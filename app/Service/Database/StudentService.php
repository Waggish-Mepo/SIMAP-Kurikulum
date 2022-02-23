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
        $orderBy = $filter['order_by'] ?? 'DESC';
        $per_page = $filter['per_page'] ?? 99;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Student::orderBy('created_at', $orderBy);

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
