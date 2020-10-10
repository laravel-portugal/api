<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimestampsColumnsToLinksTable extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn(["created_at", "updated_at", "approved_at", "deleted_at"]);
        });

        Schema::table('links', function (Blueprint $table) {
            $table->timestampsTz();
            $table->timestampTz("deleted_at")->nullable();
            $table->timestampTz("approved_at")->nullable();
        });
    }
}
    
