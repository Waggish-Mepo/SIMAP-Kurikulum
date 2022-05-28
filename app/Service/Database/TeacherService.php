<?php

namespace App\Service\Database;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Service\Functions\Username;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class TeacherService {
    public function index($filter = [])
    {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $per_page = $filter['per_page'] ?? 99;
        $withSubject = $filter['with_subject'] ?? false;
        $withUser = $filter['with_user'] ?? false;
        $name = $filter['teacher_name'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Teacher::orderBy('name', $orderBy);

        if ($withSubject) {
            $query->with('subjects');
        }

        if ($withUser) {
            $query->with('user');
        }

        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $users = $query->paginate($per_page);

        return $users;
    }

    public function detail($teacherId, $withSubject = false)
    {
        $teacher = Teacher::where('id', $teacherId);

        if ($withSubject) {
            $teacher->with('subjects');
        }

        return $teacher->first()->toArray();
    }

    public function bulkDetail($teacherIds)
    {
        $query = Teacher::whereIn('id', $teacherIds);

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
        $teacher = new Teacher;
        $teacher->id = Uuid::uuid4()->toString();
        $teacher = $this->fill($teacher, $payload);
        $teacher->save();

        $username = Username::generateUsername($teacher->name);
        $user = new User;
        $user->id = Uuid::uuid4()->toString();
        $user->name = $teacher->name;
        $user->username = $username;
        $user->password = Hash::make($username);
        $user->role = User::TEACHER;
        $user->status = true;
        $user->userable_id = $teacher->id;
        $user->save();

        return $teacher;
    }

    public function update($teacherId, $payload)
    {
        $teacher = Teacher::findOrFail($teacherId);
        $teacher = $this->fill($teacher, $payload);
        $teacher->save();

        return $teacher;
    }

    private function fill(Teacher $teacher, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $teacher->$key = $value;
        }

        $validate = Validator::make($teacher->toArray(), [
            'name' => 'required|string',
            'jk' => ['required', Rule::in(config('constant.teacher.gender'))],
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $teacher;
    }
}
