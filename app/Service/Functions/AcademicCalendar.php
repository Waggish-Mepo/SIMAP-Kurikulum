<?php

namespace App\Service\Functions;

use Carbon\Carbon;

class AcademicCalendar
{
    public function currentAcademicYear($useDash = false)
    {
        $current = Carbon::now()->format('Y');
        $next = Carbon::now()->addYears(1)->format('Y');

        if ($this->shouldNotChangeYear()) {
            $current = Carbon::now()->subYears(1)->format('Y');
            $next = Carbon::now()->format('Y');
        }

        $value = "$current/$next";

        $sessionSelectedYear = session()->get('school_year') ?? null;

        if ($sessionSelectedYear !== null) {
            $value = str_replace('-', '/', $sessionSelectedYear);
        }

        if ($useDash) {
            $value = str_replace('/', '-', $value);
        }

        return $value;
    }

    public function nextAcademicYear($useDash = false)
    {
        $current = Carbon::now()->addYears(1)->format('Y');
        $next = Carbon::now()->addYears(2)->format('Y');

        if ($this->shouldNotChangeYear()) {
            $current = Carbon::now()->format('Y');
            $next = Carbon::now()->addYears(1)->format('Y');
        }

        $value = "$current/$next";

        if ($useDash) {
            $value = str_replace('/', '-', $value);
        }

        return $value;
    }

    private function shouldNotChangeYear()
    {
        $currDate = Carbon::now();

        $nextDate = Carbon::now();
        $nextDate->setDay(19);
        // TEMPORARY
        $nextDate->setMonth(6);

        return $currDate <= $nextDate;
    }


    // Return School Year
    public function academicYearByGrade($grade, $allGrades = false)
    {
        $sessionSelectedYear = session()->get('school_year') ?? null;

        $currDate = Carbon::now();

        $nextDate = Carbon::now();
        $nextDate->setDay(19);
        // $nextDate->setMonth(6);
        // TEMPORARY
        $nextDate->setMonth(6);

        $yearNow = Carbon::now()->year;

        if ($currDate <= $nextDate) {
            $yearNow -= 1;
        }

        if ($sessionSelectedYear !== null) {
            $explodeYear = explode('-', $sessionSelectedYear);
            $yearNow = (int) $explodeYear[0];
        }

        $year = [
            '10' => $yearNow . '/' . ($yearNow + 1),
            '11' => ($yearNow - 1) . '/' . $yearNow,
            '12' => ($yearNow - 2) . '/' . ($yearNow - 1),
        ];

        if ($allGrades === true) {
            return $year;
        }

        return $year[$grade];
    }

    // Return Entry Year
    public function schoolYearByGrade($grade, $year, $allYears = false)
    {
        $sessionSelectedYear = session()->get('school_year') ?? null;

        $currDate = Carbon::now();

        $nextDate = Carbon::now();
        $nextDate->setDay(19);
        // $nextDate->setMonth(6);
        // TEMPORARY
        $nextDate->setMonth(6);
        $yearNow = Carbon::now()->year;

        if ($currDate <= $nextDate) {
            $yearNow -= 1;
        }

        if ($sessionSelectedYear !== null) {
            $explodeYear = explode('-', $sessionSelectedYear);
            $yearNow = (int) $explodeYear[0];
        }

        $currentYear = substr($yearNow, 0, 4);

        $entryYear = substr($year, 0, 4);

        if ($currentYear - $entryYear === 2) {
            $year = [
                '10' => ($yearNow - 1) . '/' . $yearNow,
                '11' => $yearNow . '/' . ($yearNow + 1),
                '12' => ($yearNow + 1) . '/' . ($yearNow + 2),
            ];
        } elseif ($currentYear - $entryYear === 1) {
            $year = [
                '10' => $yearNow . '/' . ($yearNow + 1),
                '11' => ($yearNow + 1) . '/' . ($yearNow + 2),
                '12' => ($yearNow + 2) . '/' . ($yearNow + 3),
            ];
        } elseif ($currentYear - $entryYear === 3) {
            $year = [
                '10' => ($yearNow - 2) . '/' . ($yearNow - 1),
                '11' => ($yearNow - 1) . '/' . $yearNow,
                '12' => $yearNow . '/' . ($yearNow + 1),
            ];
        } elseif ($currentYear - $entryYear === 4) {
            $year = [
                '10' => ($yearNow - 3) . '/' . ($yearNow - 2),
                '11' => ($yearNow - 2) . '/' . ($yearNow - 1),
                '12' => ($yearNow - 1) . '/' . $yearNow,
            ];
        } elseif ($currentYear - $entryYear === 5) {
            $year = [
                '10' => ($yearNow - 3) . '/' . ($yearNow - 2),
                '11' => ($yearNow - 2) . '/' . ($yearNow - 1),
                '12' => ($yearNow - 1) . '/' . $yearNow,
            ];
        } else {
            $year = [
                '10' => ($yearNow + 1) . '/' . ($yearNow + 2),
                '11' => ($yearNow + 2) . '/' . ($yearNow + 3),
                '12' => ($yearNow + 3) . '/' . ($yearNow + 4),
            ];
        }

        if ($allYears === true) {
            return $year;
        }

        return $year[$grade];
    }

    public function gradeByAcademicYear($academicCalendar, $romans = false)
    {
        $currDate = Carbon::now();
        $nextDate = Carbon::now();
        $nextDate->setDay(19);
        $nextDate->setMonth(6);

        $yearNow = Carbon::now()->year;
        if ($currDate <= $nextDate) {
            $yearNow -= 1;
        }

        if($romans) {
            $grade = [
                $yearNow . '/' . ($yearNow + 1) => 'X',
                ($yearNow - 1) . '/' . $yearNow => 'XI',
                ($yearNow - 2) . '/' . ($yearNow - 1) => 'XII',
            ];

            return $grade[$academicCalendar];
        }

        $grade = [
            $yearNow . '/' . ($yearNow + 1) => '10',
            ($yearNow - 1) . '/' . $yearNow => '11',
            ($yearNow - 2) . '/' . ($yearNow - 1) => '12',
        ];

        return $grade[$academicCalendar];
    }
    public function batchStudentGroupEntryYears($entryYear, $useGrades = false)
    {
        $firstYear = explode('/', $entryYear)[0];
        $secondYear = explode('/', $entryYear)[1];

        if ($useGrades === true) {
            return [
                1 => $firstYear . '/' . $secondYear,
                2 => ($firstYear - 1) . '/' . ($secondYear - 1),
                3 => ($firstYear - 2) . '/' . ($secondYear - 2),
                4 => ($firstYear - 3) . '/' . ($secondYear - 3),
                5 => ($firstYear - 4) . '/' . ($secondYear - 4),
                6 => ($firstYear - 5) . '/' . ($secondYear - 5),
                7 => $firstYear . '/' . $secondYear,
                8 => ($firstYear - 1) . '/' . ($secondYear - 1),
                9 => ($firstYear - 2) . '/' . ($secondYear - 2),
                10 => $firstYear . '/' . $secondYear,
                11 => ($firstYear - 1) . '/' . ($secondYear - 1),
                12 => ($firstYear - 2) . '/' . ($secondYear - 2),
            ];
        }

        return [
            $firstYear . '/' . $secondYear,
            ($firstYear - 1) . '/' . ($secondYear - 1),
            ($firstYear - 2) . '/' . ($secondYear - 2),
        ];
    }

    public function academicYears($useDash = false)
    {
        $currDate = Carbon::now();

        $nextDate = Carbon::now();
        $nextDate->setDay(19);
        $nextDate->setMonth(6);

        $yearNow = Carbon::now()->year;

        if ($currDate <= $nextDate) {
            $yearNow -= 1;
        }

        return [
            0 => $yearNow . ($useDash === true ? ' - ' : ' / ') . ($yearNow + 1),
            1 => ($yearNow - 1) . ($useDash === true ? ' - ' : ' / ') . $yearNow,
            2 => ($yearNow - 2) . ($useDash === true ? ' - ' : ' / ') . ($yearNow - 1),
        ];
    }
}

