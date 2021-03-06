<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Database\SubjectService;
use App\Service\Database\TeacherService;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjectService = new SubjectService;
        $search = $request->search;
        if($search == "") {
            $subjects = $subjectService->index(['without_pagination' => true, 'relation' => true]);

            foreach($subjects as $key => $subject) {
                $subjects[$key]['teacher_details_string'] = collect($subject['teachers'])->pluck('name')->join(', ');
            }

            return response()->json($subjects);
        } else {

            $subjects = $subjectService->index(['name' => $search,'without_pagination' => true, 'relation' => true]);

            foreach($subjects as $key => $subject) {
                $subjects[$key]['teacher_details_string'] = collect($subject['teachers'])->pluck('name')->join(', ');
            }

            return response()->json($subjects);
        }
    }

    public function getAll()
    {
        $subjectDB = new SubjectService;
        return response()->json($subjectDB->index(['without_pagination' => true]));
    }

    public function searchByCourse(Request $request)
    {
        $search = $request->search;
        $perPage = $request->per_page;

        $subjectDB = new SubjectService;
        if($search == "") {
            return response()->json($subjectDB->index(['page' => $perPage]));
        } else {
            return response()->json($subjectDB->index(['name' => $search, 'page' => $perPage]));
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
        $subjectDB = new SubjectService;
        $getOrder = $subjectDB->index(['order_by' => 'DESC', 'page' => 1]);
        if(!count($getOrder)) {
            $order = 1;
        }else {
            $order = $getOrder[0]['order'] + 1;
        }
        return response()->json($subjectDB->create([
            'name' => $request->name,
            'group' => $request->group,
            'order' => $order,
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
        $subjectDB = new SubjectService;
        $teacherService = new TeacherService;

        $subject = $subjectDB->detail($id, true);

        if(!$subject['teachers']) {
            return response()->json($subject);
        }

        $subject['teachers'] = collect($subject['teachers'])->pluck('id');

        return response()->json($subject);
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
        $subjectDB = new SubjectService;
        return response()->json($subjectDB->update($id,[
            'name' => $request->name,
            'group' => $request->group,
            'order' => $request->order,
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
        $subjectDB = new SubjectService;

        $subjectDB->delete($id);

        return response()->json(['status' => 'ok']);
    }
}
