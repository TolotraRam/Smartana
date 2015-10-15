<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('openings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('store_id');
            $table->integer('day');
            $table->timestamp('open_time');
            $table->timestamp('close_time');
        });
        Schema::table('openings', function(Blueprint $table) {
            $table->foreign('store_id')->references('store_id')->on('venues')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('openings', function(Blueprint $table) {
            $table->dropForeign('openings_store_id_foreign');
        });
        Schema::drop('openings');
    }
}
