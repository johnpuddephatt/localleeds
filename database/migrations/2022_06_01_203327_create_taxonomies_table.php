<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create("taxonomies", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table
                ->foreignUuid("parent_id")
                ->nullable()
                ->constrained("taxonomy");
            $table->string("name", 50);
            $table->string("vocabulary", 50)->nullable();
            $table->string("type", 50)->nullable();
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
        Schema::dropIfExists("taxonomies");
    }
}
