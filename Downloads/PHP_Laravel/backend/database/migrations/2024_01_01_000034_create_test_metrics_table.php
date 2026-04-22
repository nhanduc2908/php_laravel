<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_id')->constrained('test_executions')->onDelete('cascade');
            $table->string('metric_name');
            $table->float('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_metrics');
    }
};