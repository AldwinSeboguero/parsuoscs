<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnPurposeInPurposeSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_purpose_setup', function (Blueprint $table) {
            $table->dropColumn('purpose');
            $table->biginteger('purpose_id')->unsigned()->nullable();
            $table->foreign('purpose_id')
            ->references('id')->on('purpose_clearance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_purpose_setup', function (Blueprint $table) {
            //
        });
    }
}
