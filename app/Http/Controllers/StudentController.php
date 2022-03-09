<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\StudentService;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $studentGroup = $request->studentGroup;
        $students = new StudentService;

        return response()->json($students->index(['student_group_id' => $studentGroup, 'without_pagination' => true]));
    }

    public function getWithStudentGroup()
    {
        $studentDB = new StudentService;

        return response()->json($studentDB->index(['order' => true,'with_student_group' => true, 'without_pagination' => true]));
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
        $studentDB = new StudentService;
        return response()->json($studentDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentDB = new StudentService;
        return response()->json($studentDB->detail($id));
    }

    public function showWithNextPrev($id)
    {
        $studentDB = new StudentService;

        $student = $studentDB->detail($id);

        $studentNext = $studentDB->next($student['nis'], $student['student_group_id'], $id);
        if($studentNext) {
            $student['next'] = $studentNext['id'];
        } else {
            $student['next'] = null;
        }

        $studentPrev = $studentDB->prev($student['nis'], $student['student_group_id'], $id);
        if($studentPrev) {
            $student['prev'] = $studentPrev['id'];
        } else {
            $student['prev'] = null;
        }

        return response()->json($student);
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
        $studentDB = new StudentService;
        return response()->json($studentDB->update($id, $request->all()));
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
