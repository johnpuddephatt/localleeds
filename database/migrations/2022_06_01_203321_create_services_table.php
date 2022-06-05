<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->string('url', 300)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('status', 50);
            $table->text('fees')->nullable();
            $table->string('deliverable_type', 100)->nullable();
            $table->date('assured_date');
            $table->string('attending_type', 100)->nullable();
            $table->string('attending_access', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
