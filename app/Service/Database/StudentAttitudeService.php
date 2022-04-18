<?php

namespace App\Service\Database;

use App\Models\AttitudePredicate;
use App\Models\Student;
use App\Models\StudentAttitude;
use Ramsey\Uuid\Uuid;

class StudentAttitudeService {

    public function get($studentId) {
        $studentAttitude = StudentAttitude::where('student_id', $studentId)
        ->get()->toArray();

        return $studentAttitude;
    }

    public function getId($studentId, $attitudePredicateId) {
        $studentAttitude = StudentAttitude::where([
            ['student_id', $studentId],
            ['attitude_predicate_id', $attitudePredicateId]
        ])
        ->first()->toArray();

        return $studentAttitude;
    }

    public function create($payload) {

        $studentAttitude = new StudentAttitude;

        // AttitudePredicate::findOrFail($payload['attitude_predicate_id']);
        // Student::findOrFail($payload['student_id']);

        $studentAttitude->id = Uuid::uuid4()->toString();
        $studentAttitude->attitude_predicate_id = $payload['attitude_predicate_id'];
        $studentAttitude->student_id = $payload['student_id'];
        $studentAttitude->save();

        return $studentAttitude->toArray();
    }

    public function update($studentAttitudeId, $payload) {

        $studentAttitude = StudentAttitude::findOrFail($studentAttitudeId);

        // AttitudePredicate::findOrFail($payload['attitude_predicate_id']);

        $studentAttitude->attitude_predicate_id = $payload['attitude_predicate_id'];
        $studentAttitude->save();

        return $studentAttitude->toArray();
    }

    public function delete($studentAttitudeId) {

        $studentAttitude = StudentAttitude::findOrFail($studentAttitudeId);

        return $studentAttitude->toArray();
    }
}
