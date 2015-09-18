<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->boolean('enabled');
            $table->integer('country_id')->unsigned();
        });
        Schema::table('states', function(Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')
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
        Schema::table('states', function(Blueprint $table) {
            $table->dropForeign('states_country_id_foreign');
        });
        Schema::drop('states');
    }
}
