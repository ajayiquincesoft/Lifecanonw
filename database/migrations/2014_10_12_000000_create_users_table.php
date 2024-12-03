<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
			$table->integer('user_type');
			$table->integer('status');           
			$table->integer('is_varified')->default(0);
            $table->longText('profile_pic')->nullable();
            $table->longText('stripe_customer_id')->nullable(); 
            $table->longText('experience')->nullable();
            $table->longText('area_of_expertise')->nullable();
            $table->longText('description')->nullable();
            $table->string('phone')->nullable();
			$table->string('timezone')->nullable();
            $table->double('appointment_fees')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
