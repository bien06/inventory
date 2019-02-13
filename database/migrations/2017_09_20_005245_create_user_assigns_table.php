<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_assigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('item_assign');
            $table->integer('good')->nullable()->default('0')->unsigned();
            $table->integer('rejected')->nullable()->default('0')->unsigned();
            $table->string('reason')->nullable();
            $table->enum('action', ['Update', 'Void'])->nullable();
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
        Schema::dropIfExists('user_assigns');
    }
}
