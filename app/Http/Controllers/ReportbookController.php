<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\ReportbookService;
use App\Service\Database\SubjectService;
use App\Service\Database\PredicateLetterService;
use App\Service\Database\StudentService;
use App\Service\Database\ReportPeriodService;
use App\Service\Database\RegionService;
use App\Service\Database\StudentGroupService;
use App\Service\Database\MajorService;
use App\Service\Functions\Reportbook;
use PDF;
use Carbon\Carbon;

class ReportbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reportbookDB = new ReportbookService;
        $subjectDB = new SubjectService;
        $predicateLetterDB = new PredicateLetterService;

        $reportPeriodId = $request->report_period_id;
        $studentId = $request->student_id;

        $reportbook = $reportbookDB->byStudent($reportPeriodId, $studentId);

        if($reportbook) {
            $groups = [];
            foreach ($reportbook[0]['scorecard'] as $scorecard) {
                $subject = $subjectDB->detail($scorecard['gradebook']['course']['subject_id']);
                $scorecard['gradebook']['course']['subject'] = $subject;
                $scorecard['predicate_desc'] = $predicateLetterDB->detail($scorecard['predicate_letter_id']);
                if (!in_array($subject['group'], $groups)) {
                    $groups[] = $subject['group'];
                }
            }
            $reportbook[0]['subjectGroups'] = collect($groups)->sort()->values();

            return response()->json($reportbook[0]);
        } else {
            return response()->json('failed');
        }
    }

    public function checkByPeriod($period)
    {
        $reportbookDB = new ReportbookService;

        return response()->json($reportbookDB->check($period));
    }

    public function checkByStudent($student)
    {
        $reportbookDB = new ReportbookService;

        return response()->json($reportbookDB->detailByStudentId($student));
    }

    public function print(Request $request)
    {
        $reportbookDB = new ReportbookService;
        $subjectDB = new SubjectService;
        $predicateLetterDB = new PredicateLetterService;
        $studentDB = new StudentService;
        $reportPeriodDB = new ReportPeriodService;
        $regionDB = new RegionService;
        $studentGroupDB = new StudentGroupService;
        $majorDB = new MajorService;
        $reportbookFunctionService = new Reportbook;

        $reportPeriodId = $request->report_period_id;
        $studentId = $request->student_id;

        $reportbook = $reportbookDB->byStudent($reportPeriodId, $studentId);

        $entryYear = $reportbook[0]['scorecard'][0]['gradebook']['course']['entry_year'];

        $groups = [];
        foreach ($reportbook[0]['scorecard'] as $scorecard) {
            $subject = $subjectDB->detail($scorecard['gradebook']['course']['subject_id']);
            $scorecard['gradebook']['course']['subject'] = $subject;
            $scorecard['predicate_desc'] = $predicateLetterDB->detail($scorecard['predicate_letter_id']);
            if (!in_array($subject['group'], $groups)) {
                $groups[] = $subject['group'];
            }
        }

        foreach ($groups as $group) {
            $reportbook[0]['subjectGroups'][$group] = array_filter($reportbook[0]['scorecard']->toArray(), function ($sc) use ($group) {
                return ($sc['gradebook']['course']['subject']['group'] == $group);
            });
        }

        $student = $studentDB->detail($studentId);

        $region = $regionDB->detail($student['region_id'], true);

        $studentgroup = $studentGroupDB->detail($student['student_group_id']);

        $major = $majorDB->detail($studentgroup['major_id']);

        $reportperiod = $reportPeriodDB->detail($reportPeriodId);

        $date = Carbon::now()->locale('id_ID');

        $semester = $reportbookFunctionService->determineSemester($entryYear, $reportperiod['type']);

        if ((int)$semester == 1) {
            $semesterWithText = $semester." (Satu)";
        } else if ((int)$semester == 2) {
            $semesterWithText = $semester . " (Dua)";
        } else if ((int)$semester == 3) {
            $semesterWithText = $semester . " (Tiga)";
        } else if ((int)$semester == 4) {
            $semesterWithText = $semester . " (Empat)";
        } else if ((int)$semester == 5) {
            $semesterWithText = $semester . " (Lima)";
        } else if ((int)$semester == 6) {
            $semesterWithText = $semester . " (Enam)";
        }

        $data = [
            'reportbook' => $reportbook[0],
            'student' => $student,
            'reportperiod' => $reportperiod,
            'studentgroup' => $studentgroup,
            'major' => $major,
            'subjectgroup' => $reportbook[0]['subjectGroups'],
            'index' => 0,
            'date' => $date->day.' '.$date->monthName.' '.$date->year,
            'semester' => $semesterWithText,
            'headmaster_name' => config('constant.headmaster_name'),
            'student_counselor' => $region['teacher'],
        ];

        if($reportbook[0]['curriculum'] === 'K21 | Sekolah Penggerak') {
            $pdf = PDF::loadView('reportbook_k21', $data);
        } else {
            $pdf = PDF::loadView('reportbook_k13', $data);
        }

        return $pdf->download('reportbook.pdf');
    }

    public function printReportAttitude(Request $request)
    {
        $reportbookDB = new ReportbookService;
        $studentDB = new StudentService;
        $reportPeriodDB = new ReportPeriodService;
        $studentGroupDB = new StudentGroupService;
        $majorDB = new MajorService;
        $reportbookFunctionService = new Reportbook;
        $regionDB = new RegionService;

        $reportPeriodId = $request->report_period_id;
        $studentId = $request->student_id;

        $reportbook = $reportbookDB->byStudent($reportPeriodId, $studentId);

        $entryYear = $reportbook[0]['scorecard'][0]['gradebook']['course']['entry_year'];

        $student = $studentDB->detail($studentId);

        $region = $regionDB->detail($student['region_id'], true);

        $studentgroup = $studentGroupDB->detail($student['student_group_id']);

        $major = $majorDB->detail($studentgroup['major_id']);

        $reportperiod = $reportPeriodDB->detail($reportPeriodId);

        $date = Carbon::now()->locale('id_ID');

        $semester = $reportbookFunctionService->determineSemester($entryYear, $reportperiod['type']);

        if ((int)$semester == 1) {
            $semesterWithText = $semester . " (Satu)";
        } else if ((int)$semester == 2) {
            $semesterWithText = $semester . " (Dua)";
        } else if ((int)$semester == 3) {
            $semesterWithText = $semester . " (Tiga)";
        } else if ((int)$semester == 4) {
            $semesterWithText = $semester . " (Empat)";
        } else if ((int)$semester == 5) {
            $semesterWithText = $semester . " (Lima)";
        } else if ((int)$semester == 6) {
            $semesterWithText = $semester . " (Enam)";
        }

        $data = [
            'reportbook' => $reportbook[0],
            'student' => $student,
            'reportperiod' => $reportperiod,
            'studentgroup' => $studentgroup,
            'major' => $major,
            'date' => $date->day . ' ' . $date->monthName . ' ' . $date->year,
            'semester' => $semesterWithText,
            'headmaster_name' => config('constant.headmaster_name'),
            'student_counselor' => $region['teacher'] ?? [],
        ];

        $pdf = PDF::loadView('report_attitude', $data);
        return $pdf->download('attitude-report.pdf');
        // return view('report_attitude', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reportbookDB = new ReportbookService;

        $result = [];
        for ($i=0; $i < 3; $i++) {
            $init = $reportbookDB->init($request->reportPeriodId, $request->data[$i]);
            $result[] = $init;
        }

        return response()->json($result);
    }

    public function storeStudent(Request $request)
    {
        $reportbookDB = new ReportbookService;

        return response()->json($reportbookDB->create($request->reportPeriodId, $request->studentId));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reportbookDB = new ReportbookService;

        return response()->json($reportbookDB->update($id, [], ['update_type' => 'score_config']));
    }

    public function updateNote(Request $request, $id)
    {
        $reportbookDB = new ReportbookService;

        return response()->json($reportbookDB->update($id, $request->all(), ['update_type' => null]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
