<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterReportbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reportbooks', function (Blueprint $table) {
            $table->jsonb('attitude_config')->nullable();
            $table->string('attitude_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportbooks', function (Blueprint $table) {
            $table->dropColumn('attitude_config');
            $table->dropColumn('attitude_notes');
        });
    }
}
