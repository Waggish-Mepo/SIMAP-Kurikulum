<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\AttitudeService;

class AttitudeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reportPeriod = $request->report_period_id;

        $attitudeDB = new AttitudeService;

        $types = $attitudeDB->attitudes();
        $datas = [];
        foreach ($types as $type) {
            $datas[$type] = $attitudeDB->index(['report_period_id' => $reportPeriod, 'with_predicates' => true, 'type' => $type, 'without_pagination' => true]);
        }
        return response()->json($datas);
    }

    public function types()
    {
        $attitudeDB = new AttitudeService;
        return response()->json($attitudeDB->attitudes());
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
        $attitudeDB = new AttitudeService;
        $total = count($attitudeDB->index(['without_pagination' => true]));
        if($total == 0) {
            $order = 1;
        } else {
            $order = $total+1;
        }

        return response()->json($attitudeDB->create([
            'name' => $request->name,
            'order' => $order,
            'type' => $request->type,
            'report_period_id' => $request->report_period_id,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attitudeDB = new AttitudeService;

        return response()->json($attitudeDB->detail($id));
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
        $attitudeDB = new AttitudeService;

        return response()->json($attitudeDB->update($id, $request->all()));
    }

    public function editOrder(Request $request, $id)
    {
        $attitudeDB = new AttitudeService;

        $old = $attitudeDB->detail($id);
        $reportPeriod = $old['report_period_id'];

        if ($request->typeOrder === 'UP') {
            $upOrder = $old['order']-1;
            $upData = $attitudeDB->detailByOrder($upOrder, $reportPeriod);
            $upData['order'] = $old['order'];
            $attitudeDB->update($upData['id'], $upData);
            
            $old['order'] = $old['order'] - 1;
            $attitudeDB->update($id, $old);
        }

        if ($request->typeOrder === 'DOWN') {
            $downOrder = $old['order']+1;
            $downData = $attitudeDB->detailByOrder($downOrder, $reportPeriod);
            $downData['order'] = $old['order'];
            $attitudeDB->update($downData['id'], $downData);
            
            $old['order'] = $old['order'] + 1;
            $attitudeDB->update($id, $old);
        }

        return response()->json('change order successed');
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
