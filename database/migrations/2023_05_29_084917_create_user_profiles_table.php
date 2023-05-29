<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job')->nullable();
            $table->string('study_place')->nullable();
            $table->string('place_stay')->nullable();
            $table->string('place_born')->nullable();
            $table->text('bio')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('profile_photo')->default('storage/app/images/profile_picture/default_photo.png');
            $table->bigInteger('followers_number')->default(0);
            $table->enum('state',['single','engaged','married','in_relationship'])->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
