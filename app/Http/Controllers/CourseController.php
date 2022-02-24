<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\CourseService;
use App\Service\Database\MajorService;
use App\Service\Functions\AcademicCalendar;
use App\Service\Database\ReportPeriodService;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courseDB = new CourseService;
        $majorDB = new MajorService;
        $academicCalendar = new AcademicCalendar;

        $courses = $courseDB->index(['without_pagination' => true]);
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
        return response()->json($coursesWithMajors);
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

    public function getByEntryYear($id)
    {
        $courseDB = new CourseService;
        $academicCalendar = new AcademicCalendar;
        $reportPeriodDB = new ReportPeriodService;

        $reportPeriod = $reportPeriodDB->detail($id);
        $year = $reportPeriod['school_year'];

        $courses = $courseDB->index(['entry_year' => $year, 'without_pagination' => true]);
        $courseWithClass = [];
        foreach ($courses as $course) {
            $course['entry_year_with_class'] = $academicCalendar->gradeByAcademicYear($course['entry_year']);
            $courseWithClass[] = $course;
        }
        return response()->json($courseWithClass);
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
        return response()->json($courseDB->detail($id));
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
        return response()->json($courseDB->update($id, $request->all()));
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
