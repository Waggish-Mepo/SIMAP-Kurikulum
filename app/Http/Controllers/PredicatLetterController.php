<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\PredicateLetterService;

class PredicatLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($gradeBook)
    {
        $predicateDB = new PredicateLetterService;

        return response()->json($predicateDB->index(['gradebook_id' => $gradeBook, 'without_pagination' => true]));
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
        $predicateDB = new PredicateLetterService;

        return response()->json($predicateDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $predicateDB = new PredicateLetterService;

        return response()->json($predicateDB->detail($id));
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
        $predicateDB = new PredicateLetterService;

        return response()->json($predicateDB->update($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $predicateDB = new PredicateLetterService;

        $predicateDB->delete($id);

        return response()->json(['message' => 'ok']);
    }
}
