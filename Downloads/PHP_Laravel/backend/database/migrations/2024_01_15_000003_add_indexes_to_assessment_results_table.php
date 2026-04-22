<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->index('server_id');
            $table->index('criteria_id');
            $table->index('assessed_by');
        });
    }

    public function down()
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropIndex(['server_id']);
            $table->dropIndex(['criteria_id']);
            $table->dropIndex(['assessed_by']);
        });
    }
};