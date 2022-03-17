<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\TeacherService;
use App\Service\Database\SubjectTeacherService;
use App\Service\Database\SubjectService;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = new TeacherService;
        return response()->json($teachers->index(['without_pagination' => true]));
    }

    public function withSubject()
    {
        $teachersDB = new TeacherService;
        $subjectTeachersDB = new SubjectTeacherService;
        $subjectsDB = new SubjectService;

        $teachers = $teachersDB->index(['without_pagination' => true]);

        foreach ($teachers as $teacher) {
            $subjectTeachers = $subjectTeachersDB->index(['teacher_id' => $teacher['id'], 'without_pagination' => true]);

            if(!count($subjectTeachers)) {
                $teacher['subjects'] = null;
            } else {
                $subjectAssigned = [];
                foreach ($subjectTeachers as $st) {
                    $subjects = $subjectsDB->detail($st['subject_id']);
                    $subjectAssigned[] = $subjects['name'];
                }

                $teacher['subjects'] = $subjectAssigned;
            }
        }

        return response()->json($teachers);
    }

    public function accountStatistics()
    {
        $teachersDB = new TeacherService;
        return response()->json($teachersDB->accountStatistics());
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
        //
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
        //
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
