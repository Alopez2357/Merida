<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id')->index();
            $table->mediumText('headline')->nullable();
            $table->mediumText('key')->nullable();
            $table->tinyText('sub')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->jsonb('attr')->nullable();
            $table->unsignedBigInteger('load_id')->index();
            $table->foreign('load_id')
                ->on('loads')
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
        Schema::dropIfExists('data');
    }
}
