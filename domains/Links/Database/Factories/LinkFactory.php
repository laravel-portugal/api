<?php

use Domains\Links\Models\Link;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

/** @var Factory $factory */
$factory->define(Link::class, static fn (\Faker\Generator $faker) => [
    'link' => $faker->url,
    'description' => $faker->paragraph,
    'cover_image' => 'cover_images/' . UploadedFile::fake()->image('cover_image')->getFilename(),
    'author_name' => $faker->name,
    'author_email' => $faker->safeEmail,
    'created_at' => Carbon::now(),
    'approved_at' => null,
]);
