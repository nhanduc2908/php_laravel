<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->index('severity');
            $table->index('is_read');
            $table->index('is_resolved');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropIndex(['severity']);
            $table->dropIndex(['is_read']);
            $table->dropIndex(['is_resolved']);
            $table->dropIndex(['created_at']);
        });
    }
};