<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDietsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_diets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->softDeletes();

            $table->tinyInteger('kind')->nullable();  //tinyint 0~255
            $table->tinyInteger('diet_type');

            $table->float('fruits', 5, 1) ->nullable();
            $table->float('vegetables', 5, 1) ->nullable();
            $table->float('grains', 5, 1) ->nullable();
            $table->float('nuts', 5, 1) ->nullable();
            $table->float('proteins', 5, 1) ->nullable();
            $table->float('dairy', 5, 1) ->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_diets');
    }
}
