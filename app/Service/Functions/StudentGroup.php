<?php

namespace App\Service\Functions;

use App\Service\Database\BatchService;

class StudentGroup {

    //this one is cancelled
    public static function studentGroupByEntryYear($entryYear)
    {
        $academicCalendar = new AcademicCalendar;
        $batchService = new BatchService;

        $selectedYear = $academicCalendar->currentAcademicYear();

        $filterBatch = [
            'per_page' => 99,
            'entry_year' => $entryYear,
            'with_student_groups' => true
        ];

        $batches = $batchService->index($filterBatch)


        // return $studentGroups;
    }
}
