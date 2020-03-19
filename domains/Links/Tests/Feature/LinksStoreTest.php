<?php

namespace Domains\Links\Tests\Feature;

use Domains\Tags\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LinksStoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private Tag $tag;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tag = factory(Tag::class)->create();
    }

    /** @test */
    public function it_stores_resources(): void
    {
        $payload = [
            'link' => $this->faker->url,
            'description' => $this->faker->paragraph,
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->safeEmail,
            'cover_image' => UploadedFile::fake()->image('cover_image.jpg'),
            'tags' => [
                ['id' => $this->tag->id],
            ],
        ];

        $this->postJson('/links', $payload)
            ->assertNoContent();

        $this->assertDatabaseHas('links', [
            'link' => $payload['link'],
            'description' => $payload['description'],
            'author_name' => $payload['author_name'],
            'author_email' => $payload['author_email'],
            'cover_image' => 'cover_image.jpg',
            'approved_at' => null,
        ]);

        $this->assertDatabaseHas(
            'link_tag',
            [
                'tag_id' => $this->tag->id,
            ]
        );
    }

    /** @test */
    public function it_fails_to_store_resources_on_validation_errors(): void
    {
        $this->postJson('/links')
            ->assertJsonValidationErrors([
                'link', 'description', 'author_name', 'author_email', 'cover_image', 'tags',
            ]);
    }
}
