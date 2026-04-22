<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');
            $table->string('key');
            $table->text('value');
            $table->string('group')->nullable();
            $table->timestamps();
            $table->unique(['locale', 'key', 'group']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('translations');
    }
};