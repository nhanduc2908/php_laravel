<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assessment_files', function (Blueprint $table) {
            $table->index('server_id');
            $table->index('created_by');
            $table->index('status');
            $table->index('due_date');
        });
    }

    public function down()
    {
        Schema::table('assessment_files', function (Blueprint $table) {
            $table->dropIndex(['server_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
        });
    }
};