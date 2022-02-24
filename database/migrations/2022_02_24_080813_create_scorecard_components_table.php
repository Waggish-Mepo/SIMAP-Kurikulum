<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScorecardComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scorecard_components', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('gradebook_component_id');
            $table->uuid('scorecard_id');
            $table->integer('knowledge_score')->nullable();
            $table->integer('skill_score')->nullable();
            $table->integer('general_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scorecard_components');
    }
}
