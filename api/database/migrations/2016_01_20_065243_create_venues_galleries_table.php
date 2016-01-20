<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenuesGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues_galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path', 255)->nullable();
            $table->bigInteger('filesize')->unsigned()->default(0);
            $table->string('name')->nullable()->index();
            $table->string('key');
            $table->string('mime');
            $table->integer('venue_id')->nullable()->unsigned();
            $table->timestamps();
        });
        Schema::table('venues_galleries', function(Blueprint $table) {
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
        //
    }
}
