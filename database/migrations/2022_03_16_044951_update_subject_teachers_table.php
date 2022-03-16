<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubjectTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_teachers', function (Blueprint $table) {
            $table->uuid('teacher_id');
            $table->dropColumn('teachers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_teachers', function (Blueprint $table) {
            $table->jsonb('teachers');
            $table->dropColumn('teacher_id');
        });
    }
}
