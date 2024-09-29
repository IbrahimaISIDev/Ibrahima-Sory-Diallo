<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlacklistedTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklisted_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 500)->unique(); // Store the token
            $table->enum('type', ['access', 'refresh']); // Token type (access or refresh)
            $table->timestamps(); // Track creation and update times
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blacklisted_tokens');
    }
}
