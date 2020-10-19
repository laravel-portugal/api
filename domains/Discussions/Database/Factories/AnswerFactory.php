<?php

namespace Domains\Discussions\Database\Factories;

use Carbon\Carbon;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Discussions\Models\Answer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition(): array
    {
        return [
            'author_id' => UserFactory::new(),
            'question_id' => QuestionFactory::new(),
            'content' => $this->faker->paragraph,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
