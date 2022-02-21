<?php

namespace App\Service\Database;

use App\Models\Major;

class MajorService {
    public function index($filter = []) {

        $orderBy = $filter['order_by'] ?? 'ASC';
        $expertise = $filter['expertise'] ?? null;
        $name = $filter['name'] ?? null;
        $curriculum = $filter['curriculum'] ?? null;
        $perPage = $filter['page'] ?? 20;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Major::orderBy('expertise', $orderBy);

        if ($expertise) {
            $query->where('expert$expertise', $expertise);
        }

        if ($name) {
            $query->where('name', 'LIKE','%'.$name.'%');
        }

        if ($curriculum === 'K13') {
            $query->whereIn('abbreviation', config('constant.majors.abbreviations.K13'));
        }

        if ($curriculum === 'K21') {
            $query->whereIn('abbreviation', config('constant.majors.abbreviations.K21'));
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $majors = $query->paginate($perPage);

        return $majors;
    }

    public function detail($majorId) {
        $teacher = Major::findOrFail($majorId);

        return $teacher->toArray();
    }

    public function bulkDetail($majorIds)
    {
        $query = Major::whereIn('id', $majorIds);

        $majors = $query->simplePaginate(100);

        return $majors->toArray();
    }
}
