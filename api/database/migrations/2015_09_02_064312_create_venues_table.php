<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_id', 30)->unique()->index();
            $table->string('name');
            $table->string('description');
            $table->string('postal_code', 30);
            $table->string('address', 100);
            $table->string('formatted_address', 150);
            $table->string('phone', 100);
            $table->string('formatted_phone', 150);
            $table->string('facebook', 150)->nullable();
            $table->string('twitter', 150)->nullable();
            $table->string('google_plus', 150)->nullable();
            $table->text('categories')->nullable();
            $table->integer('opening_id')->nullable();
            $table->integer('review_id')->nullable()->unsigned();
            $table->integer('photo_id')->nullable()->unsigned();
            $table->string('url', 150);
            $table->boolean('is_verified')->default(0);
            $table->integer('city_id', 10)->unsigned();
            $table->integer('state_id', 10)->unsigned();
            $table->integer('country_id', 10)->unsigned();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE venues ADD location POINT' );
        Schema::table('venues', function(Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->foreign('state_id')->references('id')->on('states')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->foreign('country_id')->references('id')->on('countries')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->foreign('review_id')->references('id')->on('reviews')
                ->onDelete('restrict')
                ->onUpdate('restrict');
            $table->foreign('photo_id')->references('id')->on('photos')
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
        Schema::table('venues', function(Blueprint $table) {
            $table->dropForeign('venues_city_id_foreign');
            $table->dropForeign('venues_state_id_foreign');
            $table->dropForeign('venues_country_id_foreign');
            $table->dropForeign('venues_review_id_foreign');
            $table->dropForeign('venues_photo_id_foreign');
        });
        Schema::drop('venues');
    }
}