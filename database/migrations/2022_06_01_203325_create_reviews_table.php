<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("reviews", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("service_id");
            $table->uuid("reviewer_organisation_id")->nullable();
            $table->string("title", 100)->nullable();
            $table->string("description", 500)->nullable();
            $table->date("date")->nullable();
            $table->string("score", 100)->nullable();
            $table->string("url", 300)->nullable();
            $table->text("widget")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("reviews");
    }
}
