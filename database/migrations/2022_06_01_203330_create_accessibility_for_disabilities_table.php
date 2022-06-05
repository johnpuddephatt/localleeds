<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessibilityForDisabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create("accessibility_for_disabilities", function (
            Blueprint $table
        ) {
            $table->uuid("id")->primary();
            $table->uuid("location_id");
            $table->string("accessibility", 200);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("accessibility_for_disabilities");
    }
}
