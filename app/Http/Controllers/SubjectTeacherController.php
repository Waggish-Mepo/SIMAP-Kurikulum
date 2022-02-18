<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\SubjectTeacherService;

class SubjectTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjectTeachers = new SubjectTeacherService;
        return response()->json($subjectTeachers->index(['without_pagination' => true]));
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
        $subjectTeachers = new SubjectTeacherService;
        if($request->teachers) {
            $check = $subjectTeachers->index(['subject_id' => $id]);
            if (!count($check)) {
                return response()->json($subjectTeachers->create([
                    'subject_id' => $request->subject_id,
                    'teachers' => $request->teachers
                ]));
            } else {
                return response()->json($subjectTeachers->update(null, [
                    'subject_id' => $request->subject_id,
                    'teachers' => $request->teachers
                ], $request->subject_id));
            }
        }
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
