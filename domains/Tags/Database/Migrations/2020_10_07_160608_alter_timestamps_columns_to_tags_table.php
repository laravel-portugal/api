<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimestampsColumnsToTagsTable extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(["created_at", "updated_at", "deleted_at"]);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }
}
