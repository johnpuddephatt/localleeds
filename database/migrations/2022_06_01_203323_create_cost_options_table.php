<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("cost_options", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("service_id");
            $table->date("valid_from")->nullable();
            $table->date("valid_to")->nullable();
            $table->string("option", 200)->nullable();
            $table->tinyInteger("amount");
            $table->string("amount_description", 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("cost_options");
    }
}
