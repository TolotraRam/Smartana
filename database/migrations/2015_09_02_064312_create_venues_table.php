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
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('place_id', 30)->unique();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('postal_code', 30);
            $table->string('address', 100)->nullable();
            $table->string('formatted_address', 150);
            $table->string('phone', 100)->nullable();
            $table->string('formatted_phone', 150);
            $table->string('summary', 100)->nullable();
            $table->string('description')->nullable();
            $table->string('facebook', 150)->nullable();
            $table->string('twitter', 150)->nullable();
            $table->string('google', 150)->nullable();
            $table->string('website', 150);
            $table->boolean('is_verified')->default(0);
            $table->boolean('enabled')->default(0);
            $table->integer('city_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE venues ADD location POINT' );

        Schema::table('venues', function(Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->foreign('user_id')->references('id')->on('user')
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
        Schema::table('venues', function(Blueprint $table) {
            $table->dropForeign('venues_city_id_foreign');
            $table->dropForeign('venues_user_id_foreign');
        });
        Schema::drop('venues');
    }
}
