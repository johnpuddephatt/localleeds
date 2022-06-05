<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("service_areas", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("service_id");
            $table->string("service_area", 100)->nullable();
            $table->json("extent")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("service_areas");
    }
}
