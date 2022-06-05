<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEligibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("eligibilities", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("service_id");
            $table->tinyInteger("minimum_age");
            $table->tinyInteger("maximum_age");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("eligibilities");
    }
}
