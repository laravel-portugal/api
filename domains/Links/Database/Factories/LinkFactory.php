<?php

use Domains\Links\Models\Link;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Carbon;

/** @var $factory Factory */
$factory->define(Link::class, static fn (\Faker\Generator $faker) => [
    'link' => $faker->url,
    'description' => $faker->paragraph,
    'cover_image' => $faker->image(),
    'author_name' => $faker->name,
    'author_email' => $faker->safeEmail,
    'created_at' => Carbon::now(),
    'approved_at' => null,
]);
