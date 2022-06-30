<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClearanceRequestV2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clearance_request_v2', function (Blueprint $table) {
            $table->id();
            $table->text('token');
            $table->integer('status');
            $table->biginteger('student_id')->unsigned()->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->biginteger('signatory_id')->unsigned()->nullable();
            $table->foreign('signatory_id')->references('id')->on('signatories_v2');
            $table->biginteger('designee_id')->unsigned()->nullable();
            $table->foreign('designee_id')->references('id')->on('designees');
            $table->biginteger('purpose_id')->unsigned()->nullable();
            $table->foreign('purpose_id')->references('id')->on('purpose_clearance');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('clearance_request_v2');
    }
}
