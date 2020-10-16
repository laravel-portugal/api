<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimestampsColumnsToLinksTable extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dateTimeTz("approved_at")->nullable()->change();
            $table->dateTimeTz("created_at")->change();
            $table->dateTimeTz("updated_at")->change();
            $table->dateTimeTz("deleted_at")->change();
        });
    }
}

