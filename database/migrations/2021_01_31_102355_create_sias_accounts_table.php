<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiasAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sias_accounts', function (Blueprint $table) {
            $table->id();
            $table->biginteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->text('user_id');
            $table->text('password');
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
        Schema::dropIfExists('sias_accounts');
    }
}
