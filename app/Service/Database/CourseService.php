<?php

namespace App\Service\Database;

use App\Models\Course;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class CourseService{
    public function index($filter = []) {

        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $entryYear = $filter['entry_year'] ?? null;
        $curriculum = $filter['curriculum'] ?? null;
        $withSubject = $filter['with_subject'] ?? false;
        $withStudents = $filter['with_students'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Course::orderBy('created_at', $orderBy);

        if ($entryYear) {
            $query->where('entry_year', $entryYear);
        }

        if ($curriculum) {
            $query->where('curriculum', $curriculum);
        }

        if ($withSubject) {
            $query->with('subject');
        }

        if ($withStudents) {
            $query->with('studentCourses.student');
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $courses = $query->paginate($perPage);

        return $courses;
    }

    public function detail($courseId) {

        $course = Course::findOrFail($courseId);

        return $course->toArray();
    }

    public function curriculums() {
        return config('constant.common.curriculums');
    }

    public function create($payload) {

        $course = new Course;

        $course->id = Uuid::uuid4()->toString();
        $course = $this->fill($course, $payload);
        $course->save();

        return $course->toArray();
    }

    public function update($courseId, $payload) {

        $course = Course::findOrFail($courseId);

        $course = $this->fill($course, $payload);
        $course->save();

        return $course->toArray();
    }


    private function fill(Course $course, array $payload) {
        foreach ($payload as $key => $value) {
            $course->$key = $value;
        }

        $validate = Validator::make($course->toArray(), [
            'curriculum' => 'required|string',
            'caption' => 'required|string',
            'entry_year' => ['required', Rule::in(config('constant.common.school_years'))],
            'majors' => 'nullable',
            'subject_id' => 'required'
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $course;
    }

}