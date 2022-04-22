<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\BatchService;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $batchDB = new BatchService;
        $search = $request->search;
        $perPage = $request->per_page;

        if ($search == "") {
            return response()->json($batchDB->index(['page' => $perPage]));
        } else {
            return response()->json($batchDB->index(['batch_name' => $search, 'page' => $perPage]));
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
        $batchDB = new BatchService;
        return response()->json($batchDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $batchDB = new BatchService;
        return response()->json($batchDB->detail($id));
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
        $batchDB = new BatchService;
        return response()->json($batchDB->update($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $batchDB = new BatchService;

        $batchDB->delete($id);

        return response()->json('ok');
    }
}
