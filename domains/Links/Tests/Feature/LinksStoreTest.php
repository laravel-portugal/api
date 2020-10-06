<?php

namespace Domains\Links\Tests\Feature;

use Domains\Tags\Database\Factories\TagFactory;
use Domains\Tags\Models\Tag;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class LinksStoreTest extends TestCase
{
    use DatabaseMigrations;

    private Tag $tag;
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tag = TagFactory::new()->create();
        $this->faker = Factory::create();
    }

    /** @test */
    public function it_stores_resources(): void
    {
        Storage::fake('local');

        $payload = [
            'link' => $this->faker->url,
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->safeEmail,
            'tags' => [
                ['id' => $this->tag->id],
            ],
        ];

        $files = [
            'cover_image' => UploadedFile::fake()->image('cover_image.jpg'),
        ];

        $response = $this->call('POST', '/links', $payload, [], $files);

        self::assertTrue($response->isEmpty());

        $this->seeInDatabase('links', [
            'link' => $payload['link'],
            'title' => $payload['title'],
            'description' => $payload['description'],
            'author_name' => $payload['author_name'],
            'author_email' => $payload['author_email'],
            'cover_image' => 'cover_images/' . $files['cover_image']->hashName(),
            'approved_at' => null,
        ]);

        $this->seeInDatabase(
            'link_tag',
            [
                'tag_id' => $this->tag->id,
            ]
        );

        Storage::assertExists('cover_images/' . $files['cover_image']->hashName());
    }

    /** @test */
    public function it_fails_to_store_resources_on_validation_errors(): void
    {
        $this->post('/links')
            ->seeJsonStructure([
                'link',
                'title',
                'description',
                'author_name',
                'author_email',
                'cover_image',
                'tags',
            ]);
    }

    /** @test */
    public function it_fails_to_store_resources_with_invalid_link(): void
    {
        $payload = [
            'link' => 'this_is_not_a_valid_url',
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->safeEmail,
            'tags' => [
                ['id' => $this->tag->id],
            ],
        ];

        $this->post('/links', $payload)
            ->seeJsonStructure([
                'link',
            ]);
    }

    /** @test */
    public function it_stores_resources_with_unregistered_link_domain(): void
    {
        Storage::fake('local');

        $payload = [
            'link' => 'http://unregistered.laravel.pt',
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->safeEmail,
            'tags' => [
                ['id' => $this->tag->id],
            ],
        ];

        $files = [
            'cover_image' => UploadedFile::fake()->image('cover_image.jpg'),
        ];

        $response = $this->call('POST', '/links', $payload, [], $files);

        self::assertEquals(204, $response->getStatusCode());
        self::assertTrue($response->isEmpty());
    }
}
