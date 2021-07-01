<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrolledByGendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrolled_by_genders', function (Blueprint $table) {
          $table->id();
          $table->string('faculty');
          $table->string('program');
          $table->string('semester');
          $table->string('male_newcomers_poblado')->nullable();
          $table->string('female_newcomers_poblado')->nullable();
          $table->string('male_newcomers_rionegro')->nullable();
          $table->string('female_newcomers_rionegro')->nullable();
          $table->string('male_newcomers_apartado')->nullable();
          $table->string('female_newcomers_apartado')->nullable();
          $table->string('male_former_students_poblado')->nullable();
          $table->string('female_former_students_poblado')->nullable();
          $table->string('male_former_students_rionegro')->nullable();
          $table->string('female_former_students_rionegro')->nullable();
          $table->string('male_former_students_apartado')->nullable();
          $table->string('female_former_students_apartado')->nullable();
          $table->string('male_total_students_poblado')->nullable();
          $table->string('female_total_students_poblado')->nullable();
          $table->string('male_total_students_rionegro')->nullable();
          $table->string('female_total_students_rionegro')->nullable();
          $table->string('male_total_students_apartado')->nullable();
          $table->string('female_total_students_apartado')->nullable();
          $table->string('total')->nullable();
          $table->tinyInteger('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrolled_by_genders');
    }
}
