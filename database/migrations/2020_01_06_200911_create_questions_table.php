<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('department_id');
            $table->text('questions');
            $table->string('recipes');
            $table->string('title');
            $table->string('disease_name');
            $table->string('number');
            $table->text('biaozheng');
            $table->text('lizheng');
            $table->text('biaoli');
            $table->text('maizheng');
            $table->text('fuDaiNumber');
            $table->text('medicines');
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
        Schema::dropIfExists('questions');
    }
}
