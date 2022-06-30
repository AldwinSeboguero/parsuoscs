<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatoryToClearancerequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deficiencies', function (Blueprint $table) {
            $table->biginteger('signatory_id')->unsigned()->nullable();
            $table->foreign('signatory_id')->references('id')->on('signatories_v2');
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
