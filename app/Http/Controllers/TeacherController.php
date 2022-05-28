<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\TeacherService;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = new TeacherService;
        return response()->json($teachers->index(['without_pagination' => true]));
    }

    public function withSubject(Request $request)
    {
        $teachersDB = new TeacherService;

        $teacherSearch = $request->search;
        $perPage = $request->per_page;

        if($teacherSearch == "") {
            $teachers = $teachersDB->index(['with_subject' => true, 'with_user' => true, 'per_page' => $perPage]);
        } else {
            $teachers = $teachersDB->index(['teacher_name' => $teacherSearch,'with_subject' => true, 'with_user' => true, 'per_page' => $perPage]);
        }

        foreach ($teachers as $key => $teacher) {
            $teachers[$key]['teacher_details_string'] = collect($teacher['subjects'])->pluck('name')->join(', ');
        }
        
        return response()->json($teachers);
    }

    public function accountStatistics()
    {
        $teachersDB = new TeacherService;
        return response()->json($teachersDB->accountStatistics());
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
        $teachersDB = new TeacherService;

        return response()->json($teachersDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teachersDB = new TeacherService;

        return response()->json($teachersDB->detail(($id)));
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
        $teachersDB = new TeacherService;

        return response()->json($teachersDB->update($id, $request->all()));
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
