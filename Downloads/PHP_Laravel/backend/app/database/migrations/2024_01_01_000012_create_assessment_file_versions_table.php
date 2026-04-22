<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assessment_file_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('assessment_files')->onDelete('cascade');
            $table->integer('version');
            $table->longText('content');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assessment_file_versions');
    }
};