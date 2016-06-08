<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVenueVenueCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_venue_category', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('venue_category_id')->unsigned()->index();
            $table->integer('venue_id')->unsigned()->index();

            $table->foreign('venue_id')->references('id')->on('venues')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('venue_category_id')->references('id')->on('venue_category')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['venue_id', 'venue_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('venue_venue_category');
    }
}
