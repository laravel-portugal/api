<?php

use Domains\Accounts\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'author_id')->constrained('users');
            $table->string('title')->index();
            $table->string('slug');
            $table->text('description');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->timestampTz('resolved_at')->nullable();

            $table->index('author_id');
        });
    }
}
