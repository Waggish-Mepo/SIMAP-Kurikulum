<?php

namespace App\Service\Database;

use App\Models\Gradebook;
use App\Models\GradebookComponent;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class GradebookComponentService{

    public function index($filter = []) {
        $orderBy = $filter['order_by'] ?? 'ASC';
        $perPage = $filter['page'] ?? 20;
        $gradebook = $filter['gradebook_id'] ?? false;
        $withoutPagination = $filter['without_pagination'] ?? false;

        $query = GradebookComponent::orderBy('created_at', $orderBy);

        if ($gradebook) {
            $query->where('gradebook_id', $gradebook);
        }

        if ($withoutPagination) {
            return $query->get()->toArray();
        }
        
        $gradebookComponents = $query->paginate($perPage);

        return $gradebookComponents;
    }

    public function detail($gradebookComponentId) {

        $gradebookComponent = GradebookComponent::findOrFail($gradebookComponentId);

        return $gradebookComponent->toArray();
    }

    public function create($payload) {

        Gradebook::findOrFail($payload['gradebook_id']);
        $gradebookComponent = new GradebookComponent;

        $gradebookComponent->id = Uuid::uuid4()->toString();
        $gradebookComponent->gradebook_id = $payload['gradebook_id'];
        $gradebookComponent = $this->fill($gradebookComponent, $payload);
        $gradebookComponent->save();

        return $gradebookComponent->toArray();
    }

    //yang update belum work sepenuhnya
    public function update($gradebookComponentId, $payload) {

        Gradebook::findOrFail($payload['gradebook_id']);
        $gradebookComponent = GradebookComponent::findOrFail($gradebookComponentId);

        $gradebookComponent = $this->fill($gradebookComponent, $payload);
        $gradebookComponent->save();

        return $gradebookComponent->toArray();
    }


    private function fill(GradebookComponent $gradebookComponent, array $payload) {
        foreach ($payload as $key => $value) {
            $gradebookComponent->$key = $value;
        }

        $validate = Validator::make($gradebookComponent->toArray(), [
            'title' => 'required|string',
            'abbreviation' => 'required|string',
            'knowledge_weight' => 'required_without:general_weight|nullable|numeric|between:0,9',
            'skill_weight' => 'required_without:general_weight|nullable|numeric|between:0,9',
            'general_weight' => 'required_without_all:knowledge_weight, skill_weight|nullable|numeric|between:0,9',
            'gradebook_id' => 'required',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $gradebookComponent;
    }

}
