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
            $table->integer('question_id')->nullable();
            $table->text('question')->nullable();
            $table->text('comment')->nullable();
            $table->text('recipe')->nullable();
            $table->string('guahao');
            $table->string('state',64);
            $table->string('record_video')->nullable();
            $table->dateTime('treat_start')->nullable();
            $table->dateTime('treat_end')->nullable();
            $table->string('disease_name')->nullable();
            $table->double('price')->nullable();
            $table->double('price')->nullable();
            $table->double('price_medicine')->nullable();
            $table->double('price_guahao')->nullable();
            $table->string('pay_type_guahao',32)->nullable();
            $table->string('pay_type_medicine',32)->nullable();
            $table->double('doctor_profit')->nullable();
            $table->double('hospital_profit')->nullable();
            $table->string('original_recipe')->nullable();
            $table->string('biaozheng',256);
            $table->string('lizheng',256);
            $table->string('biaoli',256);
            $table->string('mai',256);
            $table->text('doctor_question');
            $table->string('houfang',8);
            $table->string('shippingType',8);
            $table->string('kuaidiCompany',128);
            $table->string('kuaidiNumber',128);
            $table->integer('fuNumber');
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
