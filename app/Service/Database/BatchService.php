<?php

namespace App\Service\Database;

use App\Models\Batch;
use App\Service\Functions\AcademicCalendar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class BatchService {

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $batchName = $filter['batch_name'] ?? null;
        $entryYear = $filter['entry_year'] ?? null;
        $perPage = $filter['page'] ?? 20;
        $withMajor = $filter['with_major'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = Batch::orderBy('created_at', $orderBy);

        if ($withMajor) {
            $query->with('major');
        }

        if ($batchName) {
            $query->where('batch_name', $batchName);
        }

        if ($entryYear) {
            $query->where('entry_year', $entryYear);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }

        $batchs = $query->paginate($perPage);

        return $batchs;
    }

    public function detail($batchId) {
        $batch = Batch::findOrFail($batchId);

        return $batch->toArray();
    }

    public function create($payload) {

        $batch = new Batch;

        $academicCalendarService = new AcademicCalendar;

        $batch->id = Uuid::uuid4()->toString();
        $batch->entry_year = $academicCalendarService->currentAcademicYear();
        $batch = $this->fill($batch, $payload);
        $batch->save();

        return $batch->toArray();
    }

    public function update($batchId, $payload) {

        $batch = Batch::findOrFail($batchId);

        $batch = $this->fill($batch, $payload);
        $batch->save();

        return $batch->toArray();
    }


    private function fill(Batch $batch, array $payload) {
        foreach ($payload as $key => $value) {
            $batch->$key = $value;
        }

        $validate = Validator::make($batch->toArray(), [
            'batch_name' => 'required|string',
            'entry_year' => ['required', Rule::in(config('constant.common.school_years'))],
            'major_id' => 'required',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $batch;
    }
}
