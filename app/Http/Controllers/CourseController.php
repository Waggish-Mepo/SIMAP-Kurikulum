<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\CourseService;
use App\Service\Database\MajorService;

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
            $coursesWithMajors[] = $course;
        }
        return response()->json($coursesWithMajors);
    }

    public function getCurriculums()
    {
        $courseDB = new CourseService;
        return response()->json($courseDB->curriculums());
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
