<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreeNodeNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tree_node_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tree_node_id');
            $table->string('language');
            $table->string('node_name');

            $table
                ->foreign('tree_node_id')
                ->references('node_id')
                ->on('tree_nodes')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tree_node_names', function (Blueprint $table) {
            $table->dropForeign('tree_node_names_tree_node_id_foreign')->unsigned();
        });
        Schema::dropIfExists('tree_node_names');
    }
}
