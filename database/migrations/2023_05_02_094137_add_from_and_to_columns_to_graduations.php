<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromAndToColumnsToGraduations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('graduations', function (Blueprint $table) {
            $table->timestamp('from')->nullable();
            $table->timestamp('to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('graduations', function (Blueprint $table) {
            $table->dropColumn(['from', 'to']);
        });
    }
}
