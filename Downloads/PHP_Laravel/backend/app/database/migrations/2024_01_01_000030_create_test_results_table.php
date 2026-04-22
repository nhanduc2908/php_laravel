<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->string('test_suite');
            $table->string('test_name');
            $table->enum('status', ['passed', 'failed', 'skipped']);
            $table->float('duration')->nullable();
            $table->text('message')->nullable();
            $table->text('trace')->nullable();
            $table->float('coverage')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};