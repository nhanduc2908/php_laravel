<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->integer('attempts');
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->index(['key', 'expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rate_limits');
    }
};