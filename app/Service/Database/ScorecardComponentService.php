<?php

namespace App\Service\Database;

use App\Models\Course;
use App\Models\Gradebook;
use App\Models\ReportPeriod;
use App\Models\Scorecard;
use App\Models\ScorecardComponent;
use App\Service\Functions\Gradebook as FunctionsGradebook;
use Illuminate\Support\Facades\Validator;

class ScorecardComponentService{

    public function update($reportPeriodId, $gradebookId, $scorecardId, $scorecardComponentId)
    {
        ReportPeriod::findOrFail($reportPeriodId);
        $gradebook = Gradebook::with('course')->findOrFail($gradebookId);
        $scorecard = Scorecard::findOrFail($scorecardId);

        $isK21 = $gradebook->course->curriculum === Course::K21_SEKOLAH_PENGGERAK;

        $scorecardComponent = ScorecardComponent::findOrFail($scorecardComponentId);
        $attributes = $this->request->only([
            'knowledge_score',
            'skill_score',
            'general_score',
        ]);

        $scorecardComponent = $this->fill($scorecardComponent, $attributes);

        $scorecardComponent->save();

        // FunctionsGradebook::recalculate($gradebook->id, $isK21);

        return $scorecardComponent->toArray();
    }

    private function fill(ScorecardComponent $scorecardComponent, Array $attributes)
    {
        foreach ($attributes as $key => $val) {
            $scorecardComponent->$key = $val;
        }

        $validate = Validator::make($scorecardComponent->toArray(), [
            'knowledge_score' => 'nullable|numeric|between:0,100',
            'skill_score' => 'nullable|numeric|between:0,100',
            'general_score' => 'nullable|numeric|between:0,100',
        ]);

        if($validate->fails()) {
            return $validate->errors()->toArray();
        }

        return $scorecardComponent;
    }
}
