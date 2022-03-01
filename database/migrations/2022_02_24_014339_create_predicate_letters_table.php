<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePredicateLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predicate_letters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedSmallInteger('min_score');
            $table->unsignedSmallInteger('max_score');
            $table->string('letter')->nullable();
            $table->uuid('gradebook_id');
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
        Schema::dropIfExists('predicate_letters');
    }
}
