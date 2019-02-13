<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
   Schema::create('item_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_id')->nullable();
            $table->integer('limits')->nullable()->default(0);
            $table->integer('item_id')->nullable();
            $table->integer('item_count')->nullable()->default(0)->unsigned();
            $table->integer('good')->nullable()->default(0)->unsigned();
            $table->integer('rejected')->nullable()->default(0)->unsigned();
            $table->integer('balance')->nullable()->default(0)->unsigned();
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
        Schema::dropIfExists('item_assignments');
    }
}
