<?php

namespace Domains\Links\Database\Factories;

use Domains\Links\Models\Link;
use Domains\Tags\Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition(): array
    {
        return [
            'link' => $this->faker->url,
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'cover_image' => 'cover_images/' . UploadedFile::fake()->image('cover_image')->getFilename(),
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->safeEmail,
            'created_at' => Carbon::now(),
            'approved_at' => null,
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(
            fn (Link $link) => $link->tags()->attach(
                TagFactory::new()->create()
            )
        );
    }

    public function approved(): self
    {
        return $this->state([
            'approved_at' => Carbon::now(),
        ]);
    }

    public function withAuthorEmail(string $email): self
    {
        return $this->state([
            'author_email' => $email,
        ]);
    }
}
