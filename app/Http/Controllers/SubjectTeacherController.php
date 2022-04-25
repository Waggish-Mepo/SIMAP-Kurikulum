<?php

namespace App\Http\Controllers;

use App\Service\Database\SubjectService;
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
        $subjectService = new SubjectService;

        $teacherIds = collect($request->teachers);

        $response = [];
        if(count($request->teachers)) {

            $existsSubjectTeachers = $subjectTeachers->index(['subject_id' => $id]);

            if (!count($existsSubjectTeachers)) {
                foreach($request->teachers as $teacher) {
                    $response[] = $subjectTeachers->create([
                        'subject_id' => $request->subject_id,
                        'teacher_id' => $teacher
                    ]);
                }
            } else {
                foreach ($existsSubjectTeachers as $teacher) {
                    if (!$teacherIds->contains($teacher['teacher_id'])) {
                        $subjectTeachers->delete($teacher['id']);
                    }
                }

                $existTeacherIds = collect($existsSubjectTeachers)->pluck('teacher_id');

                foreach($request->teachers as $teacher) {
                    if ($existTeacherIds->contains($teacher)) {
                        continue;
                    }

                    $response[] = $subjectTeachers->create([
                        'subject_id' => $request->subject_id,
                        'teacher_id' => $teacher
                    ]);
                }
            }
        } else {
            $subjectService->deleteTeacher($request->subject_id);

            $response = ['ok'];
        }

        return response()->json($response);
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
