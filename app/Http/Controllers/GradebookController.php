<?php

namespace App\Http\Controllers;

use App\Service\Database\BatchService;
use App\Service\Database\CourseService;
use Illuminate\Http\Request;
use App\Service\Database\GradebookService;
use App\Service\Database\ScorecardService;
use App\Service\Database\StudentCourseService;
use App\Service\Database\StudentService;

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
        $courseService = new CourseService;
        $batchService = new BatchService;
        $studentCourseService = new StudentCourseService;
        $scorecardService = new ScorecardService;

        $gradebook = $gradebookDB->create($request->all());

        $courseId = $request->course_id;

        $course = $courseService->detail($courseId);

        $batches = $batchService->index([
            'entry_year' => $course['entry_year'],
            'with_student_groups_students' => true
        ]);

        $studentGroupMajor = $batches->pluck('studentGroups')->flatten()->whereIn('major_id', $course['majors']);

        $students = $studentGroupMajor->pluck('students')->flatten();

        $studentsPayload = [];
        foreach ($students as $student) {
            $checkExist = $studentCourseService->getByStudents($student['id'], $courseId);

            if (count($checkExist)) {
                $studentsPayload[$student['id']]['id'] = $student['id'];
                $studentsPayload[$student['id']]['knowledge_score'] = null;
                $studentsPayload[$student['id']]['skill_score'] = null;
                $studentsPayload[$student['id']]['predicate'] = null;
            }
        }

        $payloadScorecard = [
            'gradebook_id' => $gradebook['id'],
            'students' => $studentsPayload,
        ];

        $scorecardService->bulkCreate($gradebook['id'], $payloadScorecard);

        return response()->json($gradebook);
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

    public function checkPeriodCourse(Request $request)
    {
        $reportPeriodId = $request->report_period;
        $courseId = $request->course;
        $gradebookDB = new GradebookService;
        return response()->json($gradebookDB->index(['report_period_id' => $reportPeriodId,'course_id' => $courseId]));
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
