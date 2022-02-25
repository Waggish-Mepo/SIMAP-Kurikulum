<?php

namespace App\Service\Database;

use App\Models\Course;
use App\Models\StudentCourse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class StudentCourseService{

    public function index($courseId, $filter = []) {
        $select = $filter['query'] ?? false;
        $orderBy = $filter['order_by'] ?? 'ASC';
        $orderByName = $filter['order_by_name'] ?? false;
        $perPage = $filter['page'] ?? 100;
        $studentGroupIds = $filter['student_group_ids'] ?? [];
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Course::findOrFail($courseId)->students();

        if ($orderByName === true) {
            $query->orderBy('students.name', $orderBy);
        } else {
            $query
            ->orderBy('students.student_group_id')
            ->orderBy('students.nis');
        }

        if ($studentGroupIds) {
            $query->whereIn('students.student_group_id', $studentGroupIds);
        }

        if ($select) {
            $course = $query->selectRaw($select);

            if ($withoutPagination) {
                return $query->get()->toArray();
            }

            return $course->paginate(100);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $studentCourses = $query->paginate($perPage);

        return $studentCourses;
    }

    public function students($courseId, $studentGroupId)
    {
        $course = Course::findOrFail($courseId);

        $students = $course->students()->where('student_group_id', $studentGroupId)
            ->orderBy('name')
            ->with('studentGroup')
            ->get();

        return $students->toArray();
    }

    public function bulkCreate($courseId, $payload) {

        $course = Course::findOrFail($courseId);

        $students = [];
        foreach ($payload['student_ids'] as $id) {
            $students[$id] = [
                'id' => Uuid::uuid4()->toString(),
            ];
        }

        $course->students()->attach($students);

        return $course->students->toArray();
    }

    public function delete($courseId, $payload)
    {
        $course = Course::findOrFail($courseId);

        $course->students()->detach($payload['student_ids']);

        return $course->students->toArray();
    }

    public function deleteStudent($courseId, $studentId)
    {
        $course = Course::findOrFail($courseId);
        $course->students()->detach($studentId);

        return "ok";
    }

}
