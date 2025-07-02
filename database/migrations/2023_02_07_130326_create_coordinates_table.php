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
        Schema::create('coordinates', function (Blueprint $table) {
            $table->id();
            $table->float('lat',11,9)->default(0.0);
            $table->float('lng',11,9)->default(0.0);
            $table->unsignedBigInteger('area_id')->index();
            $table->foreign('area_id')
                ->on('areas')
                ->references('id')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');
            $table->softDeletes();
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
        Schema::dropIfExists('coordinates');
    }
};
