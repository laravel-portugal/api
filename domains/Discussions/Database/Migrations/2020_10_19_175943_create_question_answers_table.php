<?php

use Domains\Accounts\Models\User;
use Domains\Discussions\Models\Question;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionAnswersTable extends Migration
{
    public function up(): void
    {
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'author_id');
            $table->foreignIdFor(Question::class, 'question_id');
            $table->text('content');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index('question_id');
            $table->index('author_id');
            $table->index('created_at');
        });
    }
}
