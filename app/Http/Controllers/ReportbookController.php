<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\ReportbookService;
use App\Service\Database\SubjectService;
use App\Service\Database\PredicateLetterService;

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

        $groups = [];
        foreach ($reportbook[0]['scorecard'] as $scorecard) {
            $subject = $subjectDB->detail($scorecard['gradebook']['course']['subject_id']);
            $scorecard['gradebook']['course']['subject'] = $subject;
            $scorecard['predicate_desc'] = $predicateLetterDB->detail($scorecard['predicate_letter_id']);
            if(!in_array($subject['group'], $groups)) {
                $groups[] = $subject['group'];
            }
        }
        $reportbook[0]['subjectGroups'] = $groups;
        
        return response()->json($reportbook[0]);
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
        //
    }
}
