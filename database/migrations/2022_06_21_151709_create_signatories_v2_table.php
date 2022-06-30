<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignatoriesV2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signatories_v2', function (Blueprint $table) {
            $table->id(); 
            $table->biginteger('program_id')->unsigned()->nullable();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->biginteger('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('programs');
            $table->biginteger('college_id')->unsigned()->nullable();
            $table->foreign('college_id')->references('id')->on('colleges');
            $table->biginteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name')->nullable();
            $table->biginteger('designee_id')->unsigned()->nullable();
            $table->foreign('designee_id')->references('id')->on('designees');
            $table->integer('order')->nullable();
            $table->biginteger('purpose_id')->unsigned()->nullable();
            $table->foreign('purpose_id')->references('id')->on('purposes');
            $table->biginteger('semester_id')->unsigned()->nullable();
            $table->foreign('semester_id')->references('id')->on('semesters');
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
        Schema::dropIfExists('signatories_v2');
    }
}
