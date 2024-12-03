<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('date');
            $table->string('day');
            $table->string('time');
            $table->string('schedule_by')->comment('schedule by client or reschedule by coach')->default('client');
            $table->integer('status')->comment('0 for failed and 1 for success')->default(1);
            $table->integer('repeat')->comment('0 for one time and 1 for repeat')->default(0);
            $table->dateTime('end_date')->comment('if repeat is 1 then fill end date')->nullable();
            $table->integer('payment_id')->nullable();
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
        Schema::dropIfExists('appointments');
    }
}
