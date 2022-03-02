<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\GradebookService;

class GradebookController extends Controller
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
        $gradebookDB = new GradebookService;
        return response()->json($gradebookDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gradebookDB = new GradebookService;

        return response()->json($gradebookDB->detail($id));
    }

    public function checkCourse($courseId)
    {
        $gradebookDB = new GradebookService;
        return response()->json($gradebookDB->index(['course_id' => $courseId]));
    }

    public function checkGradebook(Request $request)
    {
        $reportPeriodId = $request->report_period_id;
        $courseId = $request->course_id;

        $gradebookDB = new GradebookService;

        return response()->json($gradebookDB->index(['report_period_id' => $reportPeriodId, 'course_id' => $courseId]));
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
        $gradebookDB = new GradebookService;

        return response()->json($gradebookDB->update($id, $request->all()));
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
