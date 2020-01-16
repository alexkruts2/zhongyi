<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->integer('doctor_id');
            $table->timestamp('request_time');
            $table->integer('accept_by');
            $table->integer('question_id');
            $table->text('question');
            $table->text('comment');
            $table->text('recipe');
            $table->string('guahao');
            $table->string('state',64);
            $table->string('record_video');
            $table->dateTime('treat_start');
            $table->dateTime('treat_end');
            $table->string('disease_name');
            $table->double('price');
            $table->string('original_recipe');
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
        Schema::dropIfExists('treatments');
    }
}
