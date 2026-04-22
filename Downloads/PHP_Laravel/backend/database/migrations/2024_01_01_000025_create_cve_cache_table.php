<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cve_cache', function (Blueprint $table) {
            $table->id();
            $table->string('cve_id')->unique();
            $table->json('data');
            $table->timestamp('cached_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cve_cache');
    }
};