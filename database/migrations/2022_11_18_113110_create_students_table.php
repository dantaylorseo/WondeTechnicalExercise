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
        Schema::create('students', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('upi');
            $table->string('mis_id');
            $table->string('initials')->nullable();
            $table->string('surname');
            $table->string('forename')->nullable();
            $table->string('middle_names')->nullable();
            $table->string('legal_surname')->nullable();
            $table->string('legal_forename')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
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
        Schema::dropIfExists('students');
    }
};
