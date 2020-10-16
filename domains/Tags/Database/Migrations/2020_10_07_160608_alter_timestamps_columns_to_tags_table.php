<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimestampsColumnsToTagsTable extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dateTimeTz("created_at")->change();
            $table->dateTimeTz("updated_at")->change();
            $table->dateTimeTz("deleted_at")->change();
        });
    }
}
