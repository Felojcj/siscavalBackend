<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_students', function (Blueprint $table) {
            $table->id();
            $table->string('faculty');
            $table->string('program');
            $table->string('semester');
            $table->string('enrolled_poblado')->nullable();
            $table->string('admitted_poblado')->nullable();
            $table->string('newcomers_poblado')->nullable();
            $table->string('enrolled_rionegro')->nullable();
            $table->string('admitted_rionegro')->nullable();
            $table->string('newcomers_rionegro')->nullable();
            $table->string('enrolled_apartado')->nullable();
            $table->string('admitted_apartado')->nullable();
            $table->string('newcomers_apartado')->nullable();
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
        Schema::dropIfExists('new_students');
    }
}
