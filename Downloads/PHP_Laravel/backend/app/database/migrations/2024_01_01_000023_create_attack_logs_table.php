<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attack_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('ip');
            $table->text('payload')->nullable();
            $table->json('headers')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attack_logs');
    }
};