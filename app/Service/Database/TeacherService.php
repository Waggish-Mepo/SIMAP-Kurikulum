<?php

namespace App\Service\Database;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class TeacherService {
    public function index($filter = [])
    {
        $orderBy = $filter['order_by'] ?? 'DESC';
        $per_page = $filter['per_page'] ?? 99;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Teacher::orderBy('created_at', $orderBy);

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $users = $query->paginate($per_page);

        return $users;
    }

    public function detail($teacherId)
    {
        $teacher = Teacher::findOrFail($teacherId);

        return $teacher->toArray();
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
        $faker = Faker::create();
        $teacher = new Teacher;
        $teacher->id = Uuid::uuid4()->toString();
        $teacher = $this->fill($teacher, $payload);
        $teacher->save();

        $username = $teacher->name.$faker->numerify('####');
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
