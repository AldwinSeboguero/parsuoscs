<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSemesterDeficiencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deficiencies', function (Blueprint $table) {
            $table->biginteger('purpose_id')->unsigned()->nullable();
            $table->foreign('purpose_id')->references('id')->on('purpose_clearance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deficiencies', function (Blueprint $table) {
            //
        });
    }
}
