<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habit_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('habit_id')->unsigned();
			$table->foreign('habit_id')->references('id')->on('habits')->onDelete('cascade');
			$table->dateTime('date');
			$table->string('status')->comment('0 for pending , 1 for complete')->default(0);
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
        Schema::dropIfExists('habit_statuses');
    }
}
