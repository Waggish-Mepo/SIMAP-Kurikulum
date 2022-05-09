<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\RegionService;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $perPage = $request->per_page;
        $orderBy = $request->orderBy;
        $sort = $request->type;

        $regionDB = new RegionService;

        if ($search == "") {
            return response()->json($regionDB->index(['with_teacher' => true, 'per_page' => $perPage, 'order_by' => $orderBy, 'order_type' => $sort]));
        } else {
            return response()->json($regionDB->index(['with_teacher' => true, 'name' => $search, 'per_page' => $perPage, 'order_by' => $orderBy, 'order_type' => $sort]));
        }
    }

    public function onlyGetTeacher()
    {
        $regionDB = new RegionService;

        $regions = $regionDB->index(['without_pagination' => true]);
        $teacherIds = collect($regions)->pluck('teacher_id');

        return response()->json($teacherIds);
    }

    public function checkTeacher($teacherId)
    {
        $regionDB = new RegionService;

        return response()->json($regionDB->index(['teacher_id' => $teacherId,'without_pagination' => true]));
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
        $regionDB = new RegionService;

        return response()->json($regionDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $regionDB = new RegionService;

        return response()->json($regionDB->detail($id));
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
        $regionDB = new RegionService;

        return response()->json($regionDB->update($id, $request->all()));
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
