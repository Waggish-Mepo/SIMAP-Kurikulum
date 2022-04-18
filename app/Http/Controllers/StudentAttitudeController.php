<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\StudentAttitudeService;
use App\Service\Database\AttitudePredicateService;
use App\Service\Database\ReportbookService;

class StudentAttitudeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $studentAttitudeDB = new StudentAttitudeService;
        $reportbookDB = new ReportbookService;

        $create = $studentAttitudeDB->create([
            'attitude_predicate_id' => $request->attitude_predicate_id,
            'student_id' => $request->student_id,
        ]);

        $reportbookDB->update($request->reportbook_id, [], ['update_type' => 'attitude_config']);
        
        return response()->json($create);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($studentId)
    {
        $studentAttitudeDB = new StudentAttitudeService;

        $studentAttitudes = $studentAttitudeDB->get($studentId);

        $ids = [];
        foreach ($studentAttitudes as $data) {
            $ids[] = $data['attitude_predicate_id'];
        }

        return response()->json($ids);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $attitudePredicateDB = new AttitudePredicateService;
        $studentAttitudeDB = new StudentAttitudeService;

        $attitude = $request->attitude_id;
        $student = $request->student_id;

        $studentAttitudes = $studentAttitudeDB->get($student);
        $ids = [];
        foreach ($studentAttitudes as $data) {
            $ids[] = $data['attitude_predicate_id'];
        }

        $attitudePredicates = $attitudePredicateDB->index(['attitude_id' => $attitude, 'without_pagination' => true]);

        foreach ($attitudePredicates as $ap) {
            if (in_array($ap['id'], $ids)) {
                $getStudentAttitudeId = $studentAttitudeDB->getId($student, $ap['id']);
                return response()->json($getStudentAttitudeId['id']);
            }
        }
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
        $studentAttitudeDB = new StudentAttitudeService;
        $reportbookDB = new ReportbookService;

        $update = $studentAttitudeDB->update($id, [
            'attitude_predicate_id' => $request->attitude_predicate_id,
        ]);

        $reportbookDB->update($request->reportbook_id, [], ['update_type' => 'attitude_config']);

        return response()->json($update);
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
