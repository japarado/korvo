<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;

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
            $table->integer('ereserve_id')->unique();
            $table->string('name');
            $table->date('date_start');
            $table->text('description')->nullable();
            $table->string('classification');
            $table->text('notes')->nullable(Config::get('constants.roles.organization'));
            $table->integer('status')->unsigned()->default();
            $table->integer('read_status')->default(Config::get('constants.read_status.unread'));
            $table->bigInteger('organization_id')->unsigned()->unsigned();
            $table->bigInteger('socc_id')->unsigned()->nullable();
            $table->bigInteger('osa_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('organization_id')->references('user_id')->on('organization');
            $table->foreign('socc_id')->references('user_id')->on('socc');
            $table->foreign('osa_id')->references('user_id')->on('osa');

            $table->softDeletes();
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
