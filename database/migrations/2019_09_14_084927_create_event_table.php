<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('academic_year');
            $table->string('date_start');
            $table->text('notes');
            $table->bigInteger('organization_id')->unsigned()->unsigned();
            $table->bigInteger('socc_id')->unsigned()->nullable();
            $table->bigInteger('osa_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('users');
            $table->foreign('socc_id')->references('id')->on('users');
            $table->foreign('osa_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event');
    }
}
