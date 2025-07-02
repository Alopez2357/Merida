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
        Schema::create('area_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('area_id')->index();
            $table->foreign('area_id')
                ->on('areas')
                ->references('id')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');

            $table->unsignedBigInteger('location_id')->index();
            $table->foreign('location_id')
                ->on('data')
                ->references('id')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');
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
        Schema::dropIfExists('area_location');
    }
};
