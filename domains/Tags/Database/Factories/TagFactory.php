<?php

namespace Domains\Tags\Database\Factories;

use Carbon\Carbon;
use Domains\Tags\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'created_at' => Carbon::now(),
        ];
    }
}
