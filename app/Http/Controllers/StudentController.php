<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Database\StudentService;
use App\Service\Database\StudentGroupService;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $studentGroup = $request->studentGroup;
        $search = $request->search;
        $value = $request->search_value;
        $perPage = $request->per_page;
        $orderBy = $request->orderBy;
        $sort = $request->type;

        $students = new StudentService;

        if ($search == "") {
            return response()->json($students->index(['student_group_id' => $studentGroup, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
        } else {
            return response()->json($students->index(['student_group_id' => $studentGroup, $search => $value, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
        }
    }

    public function withAbsence($studentGroup)
    {
        $studentDB = new StudentService;

        return response()->json($studentDB->index(['student_group_id' => $studentGroup, 'with_student_absence' => true, 'without_pagination' => true]));
    }

    public function studentRegionwithAbsence($regionId)
    {
        $studentDB = new StudentService;

        return response()->json($studentDB->index(['region_id' => $regionId, 'with_student_absence' => true, 'without_pagination' => true]));
    }

    public function getWithStudentGroup()
    {
        $studentDB = new StudentService;

        return response()->json($studentDB->index(['order' => true,'with_student_group' => true, 'without_pagination' => true]));
    }

    public function getByRegion(Request $request)
    {
        $search = $request->search;
        $value = $request->search_value;
        $perPage = $request->per_page;
        $region = $request->region;
        $orderBy = $request->orderBy;
        $sort = $request->type;

        $studentDB = new StudentService;
        $studentGroupDB = new StudentGroupService;

        if ($search == "") {
            return response()->json($studentDB->index(['region_id' => $region, 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
        } else {
            if ($search == "name") {
                return response()->json($studentDB->index(['region_id' => $region, 'name' => $value, 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
            } else if ($search == "nis") {
                return response()->json($studentDB->index(['region_id' => $region, 'nis' => $value, 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
            } else if ($search == "student_group") {
                $studentGroups = $studentGroupDB->index(['name' => $value, 'without_pagination' => true]);

                if (!count($studentGroups)) {
                    return response()->json($studentDB->index(['region_id' => $region, 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
                } else {
                    if (count($studentGroups) > 1) {
                        $firstData = $studentDB->index(['region_id' => $region, 'student_group_id' => $studentGroups[0]['id'],'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'without_pagination' => true]);

                        for ($i=1; $i < count($studentGroups); $i++) {
                            $data = $studentDB->index(['region_id' => $region, 'student_group_id' => $studentGroups[$i]['id'], 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'without_pagination' => true]);
                            if (count($data) >= 1) {
                                $firstData[] = $data;
                            }
                        }

                        return response()->json([
                            'data' => $firstData,
                            'per_page' => NULL
                        ]);
                    } else {
                        $data = $studentDB->index(['region_id' => $region, 'student_group_id' => $studentGroups[0]['id'], 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'without_pagination' => true]);
                        return response()->json([
                            'data' => $data,
                            'per_page' => NULL
                        ]);
                    }
                }
            }
        }
    }

    public function getNotSignedStudent(Request $request)
    {
        $search = $request->search;
        $value = $request->search_value;
        $perPage = $request->per_page;
        $orderBy = $request->orderBy;
        $sort = $request->type;

        $studentDB = new StudentService;
        $studentGroupDB = new StudentGroupService;

        if ($search == "") {
            return response()->json($studentDB->index(['without_region' => true, 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
        } else {
            if ($search == "name") {
                return response()->json($studentDB->index(['without_region' => true, 'name' => $value, 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
            } else if ($search == "nis") {
                return response()->json($studentDB->index(['without_region' => true, 'nis' => $value, 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
            } else if ($search == "student_group") {
                $studentGroups = $studentGroupDB->index(['name' => $value, 'without_pagination' => true]);

                if (!count($studentGroups)) {
                    return response()->json($studentDB->index(['without_region' => true, 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'per_page' => $perPage]));
                } else {
                    if (count($studentGroups) > 1) {
                        $firstData = $studentDB->index(['without_region' => true, 'student_group_id' => $studentGroups[0]['id'], 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'without_pagination' => true]);

                        for ($i = 1; $i < count($studentGroups); $i++) {
                            $data = $studentDB->index(['without_region' => true, 'student_group_id' => $studentGroups[$i]['id'], 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'without_pagination' => true]);
                            if (count($data) >= 1) {
                                $firstData[] = $data;
                            }
                        }

                        return response()->json([
                            'data' => $firstData,
                            'per_page' => NULL
                        ]);
                    } else {
                        $data = $studentDB->index(['without_region' => true, 'student_group_id' => $studentGroups[0]['id'], 'with_student_group' => true, 'order_by' => $orderBy, 'order_type' => $sort, 'without_pagination' => true]);
                        return response()->json([
                            'data' => $data,
                            'per_page' => NULL
                        ]);
                    }
                }
            }
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
        $studentDB = new StudentService;
        return response()->json($studentDB->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentDB = new StudentService;
        return response()->json($studentDB->detail($id));
    }

    public function showWithNextPrev($id)
    {
        $studentDB = new StudentService;

        $student = $studentDB->detail($id);

        $studentNext = $studentDB->next($student['nis'], $student['student_group_id'], $id);
        if($studentNext) {
            $student['next'] = $studentNext['id'];
        } else {
            $student['next'] = null;
        }

        $studentPrev = $studentDB->prev($student['nis'], $student['student_group_id'], $id);
        if($studentPrev) {
            $student['prev'] = $studentPrev['id'];
        } else {
            $student['prev'] = null;
        }

        return response()->json($student);
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
        $studentDB = new StudentService;
        return response()->json($studentDB->update($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $studentDB = new StudentService;

        $studentDB->delete($id);

        return response()->json('ok');
    }
}
