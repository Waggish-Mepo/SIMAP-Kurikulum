<?php

namespace App\Service\Database;

use App\Models\AttitudePredicate;
use App\Models\Student;
use App\Models\StudentAttitude;
use Ramsey\Uuid\Uuid;

class StudentAttitudeService {

    public function create($payload) {

        $studentAttitude = new StudentAttitude;

        AttitudePredicate::findOrFail($payload['attitude_predicate_id']);
        Student::findOrFail($payload['student_id']);

        $studentAttitude->id = Uuid::uuid4()->toString();
        $studentAttitude->attitude_predicate_id = $payload['attitude_predicate_id'];
        $studentAttitude->student_id = $payload['attitude_predicate_id'];
        $studentAttitude->save();

        return $studentAttitude->toArray();
    }

    public function delete($studentAttitudeId) {

        $studentAttitude = StudentAttitude::findOrFail($studentAttitudeId);

        return $studentAttitude->toArray();
    }
}
