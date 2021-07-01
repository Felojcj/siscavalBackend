<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefectionRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defection_rates', function (Blueprint $table) {
            $table->id();
            $table->string('faculty');
            $table->string('program');
            $table->string('semester');
            $table->string('enrolled_poblado')->nullable();
            $table->string('academic_retirement_poblado')->nullable();
            $table->string('voluntary_retirement_poblado')->nullable();
            $table->string('enrolled_rionegro')->nullable();
            $table->string('academic_retirement_rionegro')->nullable();
            $table->string('voluntary_retirement_rionegro')->nullable();
            $table->string('enrolled_apartado')->nullable();
            $table->string('academic_retirement_apartado')->nullable();
            $table->string('voluntary_retirement_apartado')->nullable();
            $table->string('enrolled_total')->nullable();
            $table->string('academic_retirement_total')->nullable();
            $table->string('voluntary_retirement_total')->nullable();
            $table->string('defection_rate')->nullable();
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
        Schema::dropIfExists('defection_rates');
    }
}
