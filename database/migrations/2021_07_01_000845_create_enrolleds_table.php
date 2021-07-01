<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrolledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrolleds', function (Blueprint $table) {
            $table->id();
            $table->string('faculty');
            $table->string('program');
            $table->string('semester');
            $table->string('newcomers_poblado')->nullable();
            $table->string('former_students_poblado')->nullable();
            $table->string('total_poblado')->nullable();
            $table->string('newcomers_rionegro')->nullable();
            $table->string('former_students_rionegro')->nullable();
            $table->string('total_rionegro')->nullable();
            $table->string('newcomers_apartado')->nullable();
            $table->string('former_students_apartado')->nullable();
            $table->string('total_apartado')->nullable();
            $table->string('grand_total')->nullable();
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
        Schema::dropIfExists('enrolleds');
    }
}
