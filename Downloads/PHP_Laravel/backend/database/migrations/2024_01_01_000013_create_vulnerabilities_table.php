<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vulnerabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->string('cve')->nullable();
            $table->string('name');
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->text('description');
            $table->text('solution')->nullable();
            $table->decimal('cvss_score', 3, 1)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['open', 'fixed', 'false_positive'])->default('open');
            $table->timestamp('fixed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vulnerabilities');
    }
};