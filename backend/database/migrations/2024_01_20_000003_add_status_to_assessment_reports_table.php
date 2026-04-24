<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assessment_reports', function (Blueprint $table) {
            $table->enum('status', ['compliant', 'non_compliant', 'partial'])->default('non_compliant')->change();
        });
    }

    public function down()
    {
        Schema::table('assessment_reports', function (Blueprint $table) {
            $table->enum('status', ['compliant', 'non_compliant'])->default('non_compliant')->change();
        });
    }
};