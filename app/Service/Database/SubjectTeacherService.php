<?php

namespace App\Service\Database;

use App\Models\SubjectTeacher;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class SubjectTeacherService {
    public function index($filter = [])
    {
        $orderBy = $filter['order_by'] ?? 'DESC';
        $per_page = $filter['per_page'] ?? 99;
        $teacherId = $filter['teacher_id'] ?? null;
        $subjectId = $filter['subject_id'] ?? null;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = SubjectTeacher::orderBy('created_at', $orderBy);

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        if ($subjectId) {
            return $query->where('subject_id', $subjectId)->get()->toArray();
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $users = $query->paginate($per_page);

        return $users;
    }

    public function detail($subjectTeacherId)
    {
        $subject = SubjectTeacher::findOrFail($subjectTeacherId);

        return $subject->toArray();
    }

    public function create($payload)
    {
        $subject = new SubjectTeacher;
        $subject->id = Uuid::uuid4()->toString();
        $subject = $this->fill($subject, $payload);
        $subject->save();

        return $subject;
    }

    public function update($subjectTeacherId, $payload, $subjectId = null)
    {
        if($subjectId) {
            $subject = SubjectTeacher::where('subject_id', '=', $subjectId)->get()->first();
        }else {
            $subject = SubjectTeacher::findOrFail($subjectTeacherId);
        }

        $subject = $this->fill($subject, $payload);
        $subject->save();

        return $subject;
    }

    public function delete($subjectTeacherId) {

        $subjectTeacher = SubjectTeacher::findOrFail($subjectTeacherId);

        $subjectTeacher->delete();

        return 'ok';
    }

    private function fill($subject, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $subject->$key = $value;
        }

        $validate = Validator::make($subject->toArray(), [
            'subject_id' => 'required|string',
            'teacher_id' => 'required|string',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $subject;
    }
}
