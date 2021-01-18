<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemesterPurposeSeederClearances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clearances', function (Blueprint $table) {
            // $table->biginteger('semester_id')->unsigned()->nullable();
            // $table->foreign('semester_id')->references('id')->on('semesters');
            // $table->biginteger('purpose_id')->unsigned()->nullable();
            // $table->foreign('purpose_id')->references('id')->on('purpose_clearance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clearances', function (Blueprint $table) {
            //
        });
    }
}
