<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\ScorecardService;
use App\Service\Database\GradebookComponentService;

class ScorecardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $scorecardDB = new ScorecardService;
        $gradebookComponentDB = new GradebookComponentService;
        $gradebook = $request->gradebook_id;
        $studentGroup = $request->student_group_id;

        $scorecards = $scorecardDB->index($gradebook,
            [
                'with_scorecard_components' => true,
                'with_student' => true,
                'without_pagination' => true,
                'with_letter' => true
            ]
        );

        $scorecardFilter = [];
        foreach ($scorecards as $scorecard) {
            if ($scorecard['student']['student_group_id'] === $studentGroup) {
                $scorecardFilter[] = $scorecard;
            }
        }

        $scorecardFilterWithComponent = [];
        foreach ($scorecardFilter as $sc) {
            for ($i=0; $i < count($sc['scorecard_components']); $i++) {
                $gradebookComponent = $gradebookComponentDB->detail($sc['scorecard_components'][$i]['gradebook_component_id']);
                $sc['scorecard_components'][$i]['abbreviation'] = $gradebookComponent['abbreviation'];
                $sc['scorecard_components'][$i]['title'] = $gradebookComponent['title'];
            }
            $scorecardFilterWithComponent[] = $sc;
        }

        return response()->json($scorecardFilterWithComponent);
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
        $scorecardDB = new ScorecardService;

        return response()->json($scorecardDB->detail($id));
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
