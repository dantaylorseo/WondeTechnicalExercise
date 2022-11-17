<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wonde_classes', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('mis_id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->string('subject')->nullable();
            $table->string('alternative')->nullable();
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
        Schema::dropIfExists('wonde_classes');
    }
};
