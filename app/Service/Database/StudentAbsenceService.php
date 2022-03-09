<?php

namespace App\Service\Database;

use App\Models\StudentAbsence;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class StudentAbsenceService{
    public function index($filter = []) {

        $orderBy = $filter['order_by'] ?? 'ASC';
        $studentId = $filter['student_id'] ?? null;
        $reportPeriodId = $filter['report_period_id'] ?? null;
        $alpa = $filter['alpa'] ?? null;
        $izin = $filter['izin'] ?? null;
        $sakit = $filter['sakit'] ?? null;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = StudentAbsence::orderBy('created_at', $orderBy);

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($reportPeriodId) {
            $query->where('report_period_id', $reportPeriodId);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }
    }

    public function detail($studentAbsenceId) {

        $absence = StudentAbsence::findOrFail($studentAbsenceId);

        return $absence->toArray();
    }

    public function create($payload) {

        $studentAbsence = new StudentAbsence;

        $studentAbsence->id = Uuid::uuid4()->toString();
        $studentAbsence = $this->fill($studentAbsence, $payload);
        $studentAbsence->save();

        return $studentAbsence->toArray();
    }

    public function update($studentAbsenceId, $payload) {

        $studentAbsence = StudentAbsence::findOrFail($studentAbsenceId);

        $studentAbsence = $this->fill($studentAbsence, $payload);
        $studentAbsence->save();

        return $studentAbsence->toArray();
    }


    private function fill(StudentAbsence $studentAbsence, array $payload) {
        foreach ($payload as $key => $value) {
            $studentAbsence->$key = $value;
        }

        $validate = Validator::make($studentAbsence->toArray(), [
            'student_id' => 'required|uuid',
            'report_period_id' => 'required|uuid',
            'alpa' => 'required|numeric',
            'izin' => 'required|numeric',
            'sakit' => 'required|numeric',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $studentAbsence;
    }

}
