<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkTagTable extends Migration
{
    public function up(): void
    {
        Schema::create('link_tag', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained();
            $table->foreignId('tag_id')->constrained();
        });
    }
}
