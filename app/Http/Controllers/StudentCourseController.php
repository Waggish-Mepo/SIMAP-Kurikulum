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
        $studentService = new StudentService;
        $courseService = new CourseService;
        $batchService = new BatchService;
        $studentCourseService = new StudentCourseService;
        $courseId = $request->course;

        $course = $courseService->detail($courseId);

        $batches = $batchService->index([
            'entry_year' => $course['entry_year'],
            'with_student_groups' => true
        ]);

        $studentGroupMajor = $batches->pluck('studentGroups')->flatten()->whereIn('major_id', $course['majors']);

        $studentGroupsNameID = [];
        foreach ($studentGroupMajor as $v) {
            $studentGroupsNameID[$v['id']] = $v['name'];
        }

        $studentGroupWithStudents = [];
        foreach ($studentGroupsNameID as $student_group_id => $student_group) {
            $students = $studentService->index(['student_group_id' => $student_group_id, 'without_pagination' => true]);

            $studentWithChecked = [];
            foreach ($students as $student) {
                $checkExist = $studentCourseService->getByStudents($student['id'], $courseId);

                if (!count($checkExist)) {
                    $student['already_coursed'] = false;
                } else {
                    $student['already_coursed'] = true;
                    $studentWithChecked[] = $student;
                }
            }

            $studentGroupWithStudents[$student_group_id . '-index']['data'] = $studentWithChecked;
            $studentGroupWithStudents[$student_group_id . '-index']['name'] = $student_group;
            $studentGroupWithStudents[$student_group_id . '-index']['id'] = $student_group_id;
        }

        return response()->json($studentGroupWithStudents);
    }

    public function selectStudents($courseId)
    {
        $studentService = new StudentService;
        $courseService = new CourseService;
        $batchService = new BatchService;
        $studentCourseService = new StudentCourseService;

        $course = $courseService->detail($courseId);

        $batches = $batchService->index([
            'entry_year' => $course['entry_year'],
            'with_student_groups' => true
        ]);

        $studentGroupMajor = $batches->pluck('studentGroups')->flatten()->whereIn('major_id', $course['majors']);

        $studentGroupsNameID = [];
        foreach ($studentGroupMajor as $v) {
            $studentGroupsNameID[$v['id']] = $v['name'];
        }

        $studentGroupWithStudents = [];
        foreach ($studentGroupsNameID as $student_group_id => $student_group) {
            $students = $studentService->index(['student_group_id' => $student_group_id, 'without_pagination' => true]);

            $studentWithChecked = [];
            foreach ($students as $student) {
                $checkExist = $studentCourseService->getByStudents($student['id'], $courseId);

                if (!count($checkExist)) {
                    $student['already_coursed'] = false;
                    $studentWithChecked[] = $student;
                } else {
                    $student['already_coursed'] = true;
                }
            }

            $studentGroupWithStudents[$student_group_id.'-index']['data'] = $studentWithChecked;
            $studentGroupWithStudents[$student_group_id.'-index']['name'] = $student_group;
            $studentGroupWithStudents[$student_group_id.'-index']['id'] = $student_group_id;
        }

        return response()->json($studentGroupWithStudents);
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
        $studentCourseService = new StudentCourseService;

        $courseId = $request->course_id;
        $payload = [
            'student_ids' => $request->payload
        ];

        $createStudentCourse = $studentCourseService->bulkCreate($courseId, $payload);
        return response()->json($createStudentCourse);
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
