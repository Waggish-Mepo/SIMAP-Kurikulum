<?php

namespace App\Service\Database;

use App\Models\Gradebook;
use App\Models\ReportPeriod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class GradebookService{

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $title = $filter['title'] ?? null;
        $reportPeriodId = $filter['report_period_id'] ?? null;

        $query = Gradebook::orderBy('created_at', $orderBy);

        if ($reportPeriodId) {
            $query->where('report_period_id', $reportPeriodId);
        }

        if ($title !== null) {
            $query->where('title', 'LIKE', "%$title%");
        }

        $gradebooks = $query->paginate($perPage);

        return $gradebooks;
    }

    public function detail($gradebookId) {

        $gradebook = Gradebook::findOrFail($gradebookId);

        return $gradebook->toArray();
    }

    public function create($payload) {

        ReportPeriod::findOrFail($payload['report_period_id']);
        $gradebook = new Gradebook;

        $gradebook->id = Uuid::uuid4()->toString();
        $gradebook->report_period_id = $payload['report_period_id'];
        $gradebook = $this->fill($gradebook, $payload);
        $gradebook->save();

        return $gradebook->toArray();
    }

    public function update($gradebookId, $payload) {

        $gradebook = Gradebook::findOrFail($gradebookId);

        $gradebook = $this->fill($gradebook, $payload);
        $gradebook->save();

        return $gradebook->toArray();
    }


    private function fill(Gradebook $gradebook, array $payload) {
        foreach ($payload as $key => $value) {
            $gradebook->$key = $value;
        }

        $validate = Validator::make($gradebook->toArray(), [
            'title' => 'required|string',
            'components' => 'nullable|array',
            'components.*' => ['nullable', 'string', Rule::in(config('constant.gradebook.components'))],
            'weights' => 'nullable',
            'weights.knowledge' => 'required_without:weights.general|numeric',
            'weights.skill' => 'required_without:weights.general|numeric',
            'weights.general' => 'required_without_all:weights.knowledge,weights.skill|numeric',
            'scorebar' => 'required|numeric',
            'course_id' => 'nullable|uuid',
            'report_period_id' => 'nullable|uuid',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $gradebook;
    }

}
