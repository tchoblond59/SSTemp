<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SstempEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sstemp_emails', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('sstemp_config_id');
            $table->string('email');

            $table->foreign('sstemp_config_id')->references('id')->on('sstemp_configs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sstemp_emails');
    }
}
