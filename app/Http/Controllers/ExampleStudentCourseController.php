<?php

namespace App\Http\Controllers;

use App\Service\Database\BatchService;
use App\Service\Database\CourseService;
use App\Service\Database\StudentCourseService;
use App\Service\Database\StudentGroupService;
use App\Service\Database\StudentService;
use App\Service\Functions\AcademicCalendar;
use Illuminate\Http\Request;

class ExampleStudentCourseController extends Controller
{
    public function index($courseId) {
        $academicCalendar = new AcademicCalendar;
        $studentCourseService = new StudentCourseService;
        $batchService = new BatchService;
        $courseService = new CourseService;

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
        foreach($studentGroupName as $student_group_id => $student_group) {
            $courseStudentGroupStudent[$student_group] = $studentCourseService->students($courseId, $student_group_id);
        }

        dd($courseStudentGroupStudent);
    }

    public function assignStudents($courseId) {
        $studentCourseService = new StudentCourseService;

        $payload = [
            'student_ids' => [
                '3fc5d5be-0d9b-4cc2-877a-e990cec71ebe',
                '6c49e4ef-88d8-4723-803d-154545689d05',
                '96957111-4cb0-4763-a5ef-0b8fdf09ae12',
            ]
        ];

        $createStudentCourse = $studentCourseService->bulkCreate($courseId, $payload);

        dd($createStudentCourse);
    }

    public function selectStudents($courseId) {
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

        dd($dataStudent);
    }
}
