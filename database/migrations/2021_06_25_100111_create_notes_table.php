<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('client_id')->unsigned();
			$table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
			$table->longText('description');
			$table->dateTime('date_time');
            $table->longText('images1')->nullable();
            $table->longText('images2')->nullable();
			$table->integer('status')->comment('0 for disable , 1 for enable')->default(1);
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
        Schema::dropIfExists('notes');
    }
}
