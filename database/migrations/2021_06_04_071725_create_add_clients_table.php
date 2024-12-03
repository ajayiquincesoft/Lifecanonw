<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('client_id')->nullable();
            $table->string('client_name');
            $table->string('client_email');
            $table->string('plan_name');
            $table->double('plan_amount');
            $table->integer('status')->comment('0 for pending , 1 for accept and 2 for archieved')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('client_start_date')->nullable();
            $table->dateTime('client_end_date')->nullable();
            $table->string('code')->unique();
            $table->longText('subscription_id_for_coach')->nullable();
            $table->integer('subscription_status_for_coach')->comment('0 for disabled 1 for enabled')->nullable();
            $table->longText('subscription_id_for_client')->nullable();
            $table->integer('subscription_status_for_client')->comment('0 for disabled 1 for enabled')->nullable();
            $table->integer('cycle')->comment('plan end cycle for client')->nullable();
            $table->string('phone')->nullable();
            $table->double('appointment_fee')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('add_clients');
    }
}
