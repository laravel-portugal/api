<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinksTable extends Migration
{
    public function up(): void
    {
        Schema::create('links', static function (Blueprint $table) {
            $table->id();
            $table->text('link');
            $table->text('description');
            $table->string('author_name');
            $table->string('author_email');
            $table->string('cover_image');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('approved_at')->nullable();
        });
    }
}
