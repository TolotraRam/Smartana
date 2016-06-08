<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOpeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('openings', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('day');
            $table->timestamp('open_time');
            $table->timestamp('close_time');
            $table->integer('venue_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('venue_id')->references('id')->on('venues')
                ->onDelete('set null')
                ->onUpdate('set null');
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
            $table->dropForeign('openings_venue_id_foreign');
        });
        Schema::drop('openings');
    }
}
