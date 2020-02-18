<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('hospital_id');
            $table->string('name',128);
            $table->string('phone',64);
            $table->integer('department_id');
            $table->text('introduction')->nullable();
            $table->string('from',16);
            $table->string('to',16);
            $table->string('visiting_place',128)->nullable();
            $table->string('password',128);
            $table->string('authority',128);
            $table->integer('doctor_ratio');
            $table->string('state',32);
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
        Schema::dropIfExists('doctors');
    }
}
