<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\ReportPeriodService;

class ReportPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderBy = $request->orderBy;
        $schoolYear = $request->schoolYear;
        $search = $request->search;
        $reportPeriodDB = new ReportPeriodService;
        $perPage = $request->per_page;

        if ($orderBy == '' && $search == '') {
            return response()->json($reportPeriodDB->index(['page' => $perPage]));
        } 

        if ($search != '') {
            return response()->json($reportPeriodDB->index(['title' => $search, 'page' => $perPage]));
        }
        
        if ($orderBy != '' && $schoolYear != '') {
            return response()->json($reportPeriodDB->index(['school_year' => $schoolYear, 'page' => $perPage]));
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
        $reportPeriodDB = new ReportPeriodService;
        return response()->json($reportPeriodDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reportPeriodDB = new ReportPeriodService;
        return response()->json($reportPeriodDB->detail($id));
    }

    public function schoolYears()
    {
        $reportPeriodDB = new ReportPeriodService;
        return response()->json($reportPeriodDB->schoolYears());
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
        $reportPeriodDB = new ReportPeriodService;
        return response()->json($reportPeriodDB->update($id, $request->all()));
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
