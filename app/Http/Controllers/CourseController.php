<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\CourseService;
use App\Service\Database\MajorService;
use App\Service\Functions\AcademicCalendar;
use App\Service\Database\ReportPeriodService;
use App\Service\Database\SubjectService;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $period = $request->period;

        $courseDB = new CourseService;
        $majorDB = new MajorService;
        $academicCalendar = new AcademicCalendar;
        $subjectDB = new SubjectService;
        $reportPeriodDB = new ReportPeriodService;

        if ($search == "") {
            $subjects = $subjectDB->index(['without_pagination' => true]);
        } else {
            $subjects = $subjectDB->index(['name' => $search,'without_pagination' => true]);
        }
        
        $subjectsNameID = [];
        foreach ($subjects as $v) {
            $subjectsNameID[$v['id']] = $v['name'];
        }

        $subjectWithCourse = [];
        foreach ($subjectsNameID as $subject_id => $subject) {
            if ($period == "") {
                $courses = $courseDB->index(['subject_id' => $subject_id, 'without_pagination' => true]);
            } else {
                $reportPeriod = $reportPeriodDB->detail($period);
                $year = $reportPeriod['school_year'];

                $courses = $courseDB->index(['entry_year' => $year, 'subject_id' => $subject_id, 'without_pagination' => true]);
            }
            
            $coursesWithMajors = [];
            foreach ($courses as $course) {
                if (!$course['majors']) {
                    $coursesWithMajors[] = $course;
                    continue;
                }

                $majors = $majorDB->bulkDetail($course['majors'])['data'];
                $course['major_details'] = $majors;
                $course['major_details_string'] = collect($majors)->pluck('abbreviation')->join(', ');
                $course['entry_year_with_class'] = $academicCalendar->gradeByAcademicYear($course['entry_year']);
                $coursesWithMajors[] = $course;
            }
            $subjectWithCourse[$subject.'-index']['data'] = $coursesWithMajors;
            $subjectWithCourse[$subject.'-index']['id'] = $subject_id;
            $subjectWithCourse[$subject.'-index']['name'] = $subject;
        }

        return response()->json($subjectWithCourse);
    }

    public function getCurriculums()
    {
        $courseDB = new CourseService;
        return response()->json($courseDB->curriculums());
    }

    public function entryYears()
    {
        $academicCalendar = new AcademicCalendar;

        $grades = [
            'first' => 10,
            'second' => 11,
            'third' => 12,
        ];

        $academicYearByGrade = [
            $grades['first'] => $academicCalendar->academicYearByGrade($grades['first']),
            $grades['second'] => $academicCalendar->academicYearByGrade($grades['second']),
            $grades['third'] => $academicCalendar->academicYearByGrade($grades['third']),
        ];

        return response()->json($academicYearByGrade);
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
        $courseDB = new CourseService;
        return response()->json($courseDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $courseDB = new CourseService;
        $academicCalendar = new AcademicCalendar;
        $majorDB = new MajorService;

        $course = $courseDB->detail($id);
        $majors = $majorDB->bulkDetail($course['majors'])['data'];

        $course['entry_year_with_class'] = $academicCalendar->gradeByAcademicYear($course['entry_year']);
        $course['major_details_string'] = collect($majors)->pluck('abbreviation')->join(', ');
        return response()->json($course);
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
        $courseDB = new CourseService;
        return response()->json($courseDB->update($id, [
            'curriculum' => $request->curriculum,
            'caption' => $request->caption,
            'entry_year' => $request->entry_year,
            'majors' => $request->majors,
            'subject_id' => $request->subject_id,
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
