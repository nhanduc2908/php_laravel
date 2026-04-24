<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->index('test_suite');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->dropIndex(['test_suite']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });
    }
};