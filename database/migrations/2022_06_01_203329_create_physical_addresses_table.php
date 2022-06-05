<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create("physical_addresses", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("location_id");
            $table->string("address_1", 500)->nullable();
            $table->string("city", 50);
            $table->string("state_province", 50);
            $table->string("postal_code", 50);
            $table->string("country", 50);
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
        Schema::dropIfExists("physical_addresses");
    }
}
