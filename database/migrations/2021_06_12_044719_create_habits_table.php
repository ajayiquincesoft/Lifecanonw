<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->bigInteger('client_id')->unsigned();
			$table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('name');
			$table->string('repeat')->nullable();
			$table->integer('status')->comment('0 for disable , 1 for enable and 2 for complete')->default(1);
			$table->integer('all_day')->comment('0 for not , 1 for yes')->default(0);
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
            $table->string('alert')->comment('At time of habit ,5 minutes before,10 minutes before,15 minutes before,30 minutes before,1 hour before,2 hour before,1 day beofre')->nullable();
            $table->integer('number_of_session')->comment('default')->default(0);
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
        Schema::dropIfExists('habits');
    }
}
