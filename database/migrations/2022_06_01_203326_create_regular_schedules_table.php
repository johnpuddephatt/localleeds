<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegularSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("regular_schedules", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("service_id");
            $table->uuid("location_id");
            $table->integer("weekday");
            $table->timeTz("opens_at");
            $table->timeTz("closes_at");
            $table->date("valid_from");
            $table->date("valid_to");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("regular_schedules");
    }
}
