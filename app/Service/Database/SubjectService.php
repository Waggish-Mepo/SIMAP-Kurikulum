<?php

namespace App\Service\Database;

use App\Models\Subject;
use App\Models\SubjectTeacher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class SubjectService{
    public function index($filter = []) {

        $orderBy = $filter['order_by'] ?? 'ASC';
        $group = $filter['group'] ?? null;
        $withRelation = $filter['relation'] ?? false;
        $perPage = $filter['page'] ?? 20;

        $query = Subject::orderBy('order', $orderBy);

        if ($group) {
            $query->where('group', $group);
        }

        if($withRelation) {
            $query->with('subjectTeacher');
        }

        $subjects = $query->paginate($perPage);

        return $subjects;
    }

    public function detail($subjectId) {

        $subject = Subject::findOrFail($subjectId);

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
