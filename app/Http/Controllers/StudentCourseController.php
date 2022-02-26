<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\BatchService;
use App\Service\Database\CourseService;
use App\Service\Database\StudentCourseService;
use App\Service\Database\StudentGroupService;
use App\Service\Database\StudentService;
use App\Service\Functions\AcademicCalendar;

class StudentCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $academicCalendar = new AcademicCalendar;
        $studentCourseService = new StudentCourseService;
        $batchService = new BatchService;
        $courseService = new CourseService;
        $courseId = $request->course;

        $course = $courseService->detail($courseId);

        $batches = $batchService->index([
            'entry_year' => $course['entry_year'],
            'with_student_groups' => true
        ]);

        $studentGroups = $batches->pluck('studentGroups')->flatten();

        $studentGroupName = [];
        foreach ($studentGroups as $v) {
            $studentGroupName[$v['id']] = $v['name'];
        }

        $grade = $academicCalendar->gradeByAcademicYear($course['entry_year'], true);

        $courseStudentGroupStudent = [];
        foreach ($studentGroupName as $student_group_id => $student_group) {
            $courseStudentGroupStudent[$student_group_id.'-index']['data'] = $studentCourseService->students($courseId, $student_group_id);
            $courseStudentGroupStudent[$student_group_id.'-index']['name'] = $student_group;
            $courseStudentGroupStudent[$student_group_id.'-index']['id'] = $student_group_id;
        }


        return response()->json($courseStudentGroupStudent);
    }

    public function selectStudents($courseId)
    {
        $academicCalendar = new AcademicCalendar;
        $studentService = new StudentService;
        $batchService = new BatchService;
        $courseService = new CourseService;
        $studentCourseService = new StudentCourseService;

        $course = $courseService->detail($courseId);

        $batches = $batchService->index([
            'entry_year' => $course['entry_year'],
            'with_student_groups_students' => true
        ]);

        $studentGroups = $batches->pluck('studentGroups')->flatten();

        $students = $studentGroups->pluck('students')->flatten();

        $studentWithCourse = $studentCourseService->index($courseId, ['query' => 'students.id', 'without_pagination' => true]);

        $studentIdCourseCreated = [];
        if (count($studentWithCourse) > 0) {
            $studentIdCourseCreated = collect($studentWithCourse)->pluck('id', 'id')->all();
        }

        $dataStudent = [];
        foreach ($students as $key => $v) {
            $dataStudent[$key] = $v;

            if (!isset($studentIdCourseCreated[$v['id']])) {
                $dataStudent[$key]['already_coursed'] = false;

                continue;
            }

            $dataStudent[$key]['already_coursed'] = true;
        }

        return response()->json($dataStudent);
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
