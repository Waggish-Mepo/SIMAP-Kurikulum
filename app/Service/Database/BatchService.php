<?php

namespace App\Service\Database;

use App\Models\Batch;
use App\Service\Functions\AcademicCalendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class BatchService {

    public function index($filter = []) {
        $order = $filter['order'] ?? 'created_at';
        $orderBy = $filter['order_by'] ?? 'ASC';
        $batchName = $filter['batch_name'] ?? null;
        $entryYear = $filter['entry_year'] ?? null;
        $perPage = $filter['page'] ?? 20;
        $withMajor = $filter['with_major'] ?? false;
        $withStudentGroups = $filter['with_student_groups'] ?? false;
        $withStudentGroupsStudents = $filter['with_student_groups_students'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Batch::orderBy($order, $orderBy);

        if ($withMajor) {
            $query->with('major');
        }

        if ($withStudentGroups) {
            $query->with('studentGroups');
        }

        if ($withStudentGroupsStudents) {
            $query->with('studentGroups.students');
        }

        if ($batchName) {
            $query->where('batch_name','LIKE', '%'. $batchName . '%');
        }

        if ($entryYear) {
            $query->where('entry_year', $entryYear);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $batchs = $query->paginate($perPage);

        return $batchs;
    }

    public function detail($batchId) {
        $batch = Batch::findOrFail($batchId);

        return $batch->toArray();
    }

    public function create($payload) {

        $batch = new Batch;

        $academicCalendarService = new AcademicCalendar;

        $batch->id = Uuid::uuid4()->toString();
        $batch->entry_year = $academicCalendarService->currentAcademicYear();
        $batch = $this->fill($batch, $payload);
        $batch->save();

        return $batch->toArray();
    }

    public function update($batchId, $payload) {

        $batch = Batch::findOrFail($batchId);

        $batch = $this->fill($batch, $payload);
        $batch->save();

        return $batch->toArray();
    }

    public function delete($batchId) {

        $batch = Batch::findOrFail($batchId);

        $studentGroups = $batch->studentGroups();

        DB::transaction(function () use ($batch, $studentGroups) {

            foreach ($studentGroups as $studentGroup) {
                $students = $studentGroup->students();

                foreach($students as $student) {
                    $scorecards = $student->scorecards();

                    if(count($scorecards->get())) {
                        $scorecards->scorecardComponents()->delete();
                    }

                    $scorecards->delete();
                    $student->absences()->delete();
                    $student->courses()->detach();
                    $student->user()->delete();
                    $student->reportbooks()->delete();
                }
                $students->delete();
            }

            $studentGroups->delete();
            $batch->delete();
        });

        return 'ok';
    }


    private function fill(Batch $batch, array $payload) {
        foreach ($payload as $key => $value) {
            $batch->$key = $value;
        }

        $validate = Validator::make($batch->toArray(), [
            'batch_name' => 'required|string',
            'entry_year' => ['required', Rule::in(config('constant.common.school_years'))],
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $batch;
    }
}
