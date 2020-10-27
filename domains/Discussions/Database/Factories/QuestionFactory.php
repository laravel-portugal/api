<?php

namespace Domains\Discussions\Database\Factories;

use Carbon\Carbon;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Discussions\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'author_id' => UserFactory::new(),
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
