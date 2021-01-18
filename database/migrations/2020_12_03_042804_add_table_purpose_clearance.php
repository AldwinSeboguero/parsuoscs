<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTablePurposeClearance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purpose_clearance', function (Blueprint $table) {
            $table->id();
            $table->biginteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->json('purpose'); 
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
        // Schema::dropIfExists('purpose_clearance');
    }
}
