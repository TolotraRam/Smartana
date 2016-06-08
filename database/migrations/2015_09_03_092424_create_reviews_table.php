<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('score');
            $table->text('message');
            $table->integer('user_id')->unsigned();
            $table->integer('venue_id')->unsigned()->nullable();
            $table->timestamp('published_at');
            $table->timestamps();
        });
        Schema::table('reviews', function(Blueprint $table) {
            $table->foreign('venue_id')->references('id')->on('venues')
                ->onDelete('set null')
                ->onUpdate('set null');
                
            $table->foreign('user_id')->references('id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function(Blueprint $table) {
            $table->dropForeign('reviews_venue_id_foreign');
        });
        Schema::drop('reviews');
    }
}
