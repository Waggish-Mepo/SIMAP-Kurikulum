<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Database\SubjectService;
use App\Service\Database\TeacherService;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjectService = new SubjectService;
        $teacherService = new TeacherService;
        $search = $request->search;
        if($search == "") {
            $subjects = $subjectService->index(['without_pagination' => true, 'relation' => true]);

            $subjectsWithTeacher = [];
            foreach ($subjects as $subject) {
                if(!$subject['subject_teacher']) {
                    $subjectsWithTeacher[] = $subject;
                    continue;
                }

                $teachers = $teacherService->bulkDetail($subject['subject_teacher']['teachers'])['data'];
                $subject['teacher_details'] = $teachers;
                $subject['teacher_details_string'] = collect($teachers)->pluck('name')->join(', ');
                $subjectsWithTeacher[] = $subject;
            }

            return response()->json($subjectsWithTeacher);
        } else {

            $subjects = $subjectService->index(['name' => $search, 'page' => 50, 'relation' => true]);

            $subjectsWithTeacher = [];
            foreach ($subjects as $subject) {
                if(!$subject['subject_teacher']) {
                    continue;
                }

                $teachers = $teacherService->bulkDetail($subject['subject_teacher']['teachers'])['data'];
                $subject['teacher_details_string'] = collect($teachers)->pluck('name')->join(', ');
                $subjectsWithTeacher[] = $subject;
            }

            return response()->json($subjectsWithTeacher);
        }
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
        $subjectDB = new SubjectService;
        $getOrder = $subjectDB->index(['order_by' => 'DESC', 'page' => 1]);
        if(!count($getOrder)) {
            $order = 1;
        }else {
            $order = $getOrder[0]['order'] + 1;
        }
        return response()->json($subjectDB->create([
            'name' => $request->name,
            'group' => $request->group,
            'order' => $order,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subjectDB = new SubjectService;
        $teacherService = new TeacherService;

        $subject = $subjectDB->detail($id, true);

        if(!$subject['subject_teacher']) {
            return response()->json($subject);
        }

        $teachers = $teacherService->bulkDetail($subject['subject_teacher']['teachers'])['data'];
        $subject['teachers'] = $teachers;

        return response()->json($subject);
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
        $subjectDB = new SubjectService;
        return response()->json($subjectDB->update($id,[
            'name' => $request->name,
            'group' => $request->group,
            'order' => $request->order,
        ]));
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
