<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreeNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tree_nodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('node_id')->unique();
            $table->integer('level');
            $table->integer('i_left');
            $table->integer('i_right');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tree_nodes');
    }
}
