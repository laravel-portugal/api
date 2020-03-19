<?php

use Carbon\Carbon;
use Domains\Tags\Models\Tag;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Tag::class, static fn (\Faker\Generator $faker) => [
    'name' => $faker->name,
    'created_at' => Carbon::now(),
]);
