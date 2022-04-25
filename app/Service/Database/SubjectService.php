<?php

namespace App\Service\Database;

use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class SubjectService{
    public function index($filter = []) {

        $orderBy = $filter['order_by'] ?? 'ASC';
        $group = $filter['group'] ?? null;
        $name = $filter['name'] ?? null;
        $withRelation = $filter['relation'] ?? false;
        $perPage = $filter['page'] ?? 20;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Subject::orderBy('order', $orderBy);

        if ($group) {
            $query->where('group', $group);
        }

        if ($name) {
            $query->where('name', 'LIKE','%'.$name.'%');
        }

        if($withRelation) {
            $query->with('teachers');
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $subjects = $query->paginate($perPage);

        return $subjects;
    }

    public function detail($subjectId, $withRelation = false) {

        $query = Subject::where('id', $subjectId);

        if($withRelation) {
            $query->with('teachers');
        }

        $subject = $query->first();

        return $subject->toArray();
    }

    public function create($payload) {

        $subject = new Subject;

        $subject->id = Uuid::uuid4()->toString();
        $subject = $this->fill($subject, $payload);
        $subject->save();

        return $subject->toArray();
    }

    public function update($subjectId, $payload) {

        $subject = Subject::findOrFail($subjectId);

        $subject = $this->fill($subject, $payload);
        $subject->save();

        return $subject->toArray();
    }

    public function delete($subjectId) {
        $subject = Subject::findOrFail($subjectId);
        DB::transaction(function () use ($subject) {
            $courses = $subject->courses;

            foreach($courses as $course) {
                $course->gradebookComponents()->delete();
                $course->scorecards()->delete();
                $course->predicateLetters()->delete();
                $course->gradebooks()->delete();
                $course->students()->detach();
                $course->delete();
            }

            $subject->subjectTeachers()->delete();
            $subject->delete();
        });

        return 'ok';
    }

    public function deleteTeacher($subjectId) {
        $subject = Subject::findOrFail($subjectId);

        DB::transaction(function () use ($subject) {
            $subject->subjectTeachers()->delete();
        });

        return 'ok';
    }


    private function fill(Subject $subject, array $payload) {
        foreach ($payload as $key => $value) {
            $subject->$key = $value;
        }

        $validate = Validator::make($subject->toArray(), [
            'name' => 'required|string',
            'group' => ['required', Rule::in(config('constant.subject.group'))],
            'order' => 'required|numeric',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $subject;
    }

}
