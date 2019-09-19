<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_student', function (Blueprint $table) {

            $table->bigInteger('event_id')->unsigned();
            $table->string('student_id');
            $table->timestamps();

            $table->primary(['event_id', 'student_id']);
            
            $table->foreign('event_id')->references('id')->on('event');
            $table->foreign('student_id')->references('id')->on('student');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_student');
    }
}
