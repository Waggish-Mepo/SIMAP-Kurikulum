<?php

namespace App\Service\Database;

use App\Models\Course;
use App\Models\Gradebook;
use App\Models\ReportPeriod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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

        $gradebookArray = $gradebook->toArray();

        unset($gradebookArray['components']);

        if (array_key_exists('components', $payload) && $payload['components'] !== null) {
            $gradebookArray['components'] = (array) $gradebook['components'] ?? null;
            if (isset($gradebook['weights']->knowledge)) {
                $gradebook['weights->knowledge'] = $gradebook->weights->knowledge / 100;
            }
        }

        unset($gradebookArray['weights']);

        if (array_key_exists('weights', $payload) && $payload['weights'] !== null) {
            $gradebookArray['weights'] = (array) $gradebook['weights'] ?? null;
            if (isset($gradebook['weights']->skill)) {
                $gradebook['weights->skill'] = $gradebook->weights->skill / 100;
            }
        }

        $course = Course::findOrFail($gradebook->course_id);

        if ($course->curriculum === Course::K21_SEKOLAH_PENGGERAK) {
            $gradebook['weights->general'] = 1;
        }

        if ($course->curriculum !== Course::K21_SEKOLAH_PENGGERAK) {
            if ($gradebook->weights->knowledge + $gradebook->weights->skill !== 1.0) {
                throw new UnprocessableEntityHttpException('total weights must 100');
            }
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
