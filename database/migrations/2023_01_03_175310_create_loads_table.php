<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('headline')->nullable();
            $table->string('file')->nullable();

            $table->unsignedBigInteger('type_id')->index();
            $table->foreign('type_id')
                ->on('types')
                ->references('id')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');

            $table->unsignedBigInteger('source_id')->index();
            $table->foreign('source_id')
                ->on('sources')
                ->references('id')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')
                ->on('users')
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
        Schema::dropIfExists('loads');
    }
}
