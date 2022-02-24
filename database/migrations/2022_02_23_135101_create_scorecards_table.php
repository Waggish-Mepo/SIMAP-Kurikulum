<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScorecardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scorecards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('gradebook_id');
            $table->string('predicate')->nullable()->index();
            $table->decimal('knowledge_score', 5, 2)->unsigned()->nullable();
            $table->decimal('skill_score', 5, 2)->unsigned()->nullable();
            $table->decimal('general_score', 5, 2)->unsigned()->nullable();
            $table->decimal('final_score', 5, 2)->unsigned()->nullable();
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
        Schema::dropIfExists('scorecards');
    }
}
