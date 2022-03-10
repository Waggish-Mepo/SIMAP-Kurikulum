<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\StudentGroupService;
use App\Service\Database\CourseService;
use App\Service\Database\BatchService;

class StudentGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $sort = $request->sort;
        $batch = $request->batch;
        $perPage = $request->per_page;
        $studentGroupDB = new StudentGroupService;

        if ($search == "" && $sort == "") {
            $studentGroups = $studentGroupDB->index(['batch_id' => $batch, 'with_major' => true, 'page' => $perPage]);

            return response()->json($studentGroups);
        } 
        else if ($search != "") {
            $studentGroups = $studentGroupDB->index(['batch_id' => $batch, 'name' => $search, 'with_major' => true, 'group_by_major' => true, 'page' => $perPage]);

            return response()->json($studentGroups);
        } 
        else if ($sort != "") {
            $studentGroups = $studentGroupDB->index(['batch_id' => $batch, 'major_id' => $sort, 'with_major' => true, 'group_by_major' => true, 'page' => $perPage]);

            return response()->json($studentGroups);
        }
    }

    public function getAll()
    {
        $studentGroupDB = new StudentGroupService;

        return response()->json($studentGroupDB->index(['without_pagination' => true]));
    }

    public function getByCourse($courseId)
    {
        $courseService = new CourseService;
        $batchService = new BatchService;

        $course = $courseService->detail($courseId);

        $batches = $batchService->index([
            'entry_year' => $course['entry_year'],
            'with_student_groups' => true
        ]);

        $studentGroupMajor = $batches->pluck('studentGroups')->flatten()->whereIn('major_id', $course['majors']);

        $result = [];
        foreach ($studentGroupMajor as $sgMajor) {
            $result[] = $sgMajor;
        }

        return response()->json($result);
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
        $studentGroupDB = new StudentGroupService;
        return response()->json($studentGroupDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentGroupDB = new StudentGroupService;
        return response()->json($studentGroupDB->detail($id));
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
        $studentGroupDB = new StudentGroupService;
        return response()->json($studentGroupDB->update($id, $request->all()));
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
