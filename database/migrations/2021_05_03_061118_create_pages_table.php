<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();$table->string('page_title')->nullable();
            $table->string('slug')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_data')->nullable();
            $table->longText('page_content')->nullable();
            $table->enum('status', ['0', '1'])->comment('0 for Inactive and 1 for active')->default('1');
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
        Schema::dropIfExists('pages');
    }
}
