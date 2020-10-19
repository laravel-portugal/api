<?php

use Domains\Discussions\Models\Question;
use Domains\Accounts\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'author_id');
            $table->foreignIdFor(Question::class, 'question_id');
            $table->text('content');
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index('question_id');
            $table->index('author_id');
        });
    }
}
