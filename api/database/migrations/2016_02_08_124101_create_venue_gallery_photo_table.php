<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenueGalleryPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_gallery_photo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path', 255)->nullable();
            $table->bigInteger('filesize')->unsigned()->default(0);
            $table->string('name')->nullable()->index();
            $table->string('key');
            $table->string('mime');
            $table->integer('venue_gallery_id')->nullable()->unsigned();
            $table->timestamps();
        });
        Schema::table('venue_gallery_photo', function(Blueprint $table) {
            $table->foreign('venue_gallery_id')->references('id')->on('venue_gallery')
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
