<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habit_items', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->bigInteger('habit_id')->unsigned();
			$table->foreign('habit_id')->references('id')->on('habits')->onDelete('cascade');
			$table->string('item_name');
			$table->string('item_time');
			$table->string('item_status')->comment('0 for pending , 1 for complete')->default(0);
			$table->dateTime('next_date')->comment('it is for when it will repeat')->nullable();
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
        Schema::dropIfExists('habit_items');
    }
}
