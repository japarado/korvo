<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSpeakerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_speaker', function (Blueprint $table) {
            $table->bigInteger('event_id')->unsigned();
            $table->bigInteger('speaker_id')->unsigned();
            $table->timestamps();

            $table->primary(['event_id', 'speaker_id']);

            $table->foreign('event_id')->references('id')->on('event');
            $table->foreign('speaker_id')->references('id')->on('speaker');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_speaker');
    }
}
