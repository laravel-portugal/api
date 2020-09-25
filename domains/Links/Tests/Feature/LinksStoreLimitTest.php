<?php

namespace Domains\Links\Tests\Feature;

use Domains\Tags\Database\Factories\TagFactory;
use Domains\Tags\Models\Tag;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class LinksStoreLimitTest extends TestCase
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
    public function it_fails_to_store_resources_when_exceeding_unapproved_limit(): void
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

        // set a random limit
        $limit = rand(5, 20);
        config(['links.max_unapproved_links' => $limit]);

        while ($limit > 0) {
            $limit --;
            $response = $this->call('POST', '/links', $payload, [], $files);
            self::assertTrue($response->isEmpty());
        }

        $response = $this->call('POST', '/links', $payload, [], $files);
        self::assertEquals(Response::HTTP_TOO_MANY_REQUESTS, $response->getStatusCode());
    }

    /** @test */
    public function it_stores_resources_above_unapproved_limit_when_from_another_author(): void
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

        // set a random limit
        $limit = rand(5, 20);
        config(['links.max_unapproved_links' => $limit]);

        while ($limit > 0) {
            $limit --;
            $response = $this->call('POST', '/links', $payload, [], $files);
            self::assertTrue($response->isEmpty());
        }

        // modify the author_email
        $payload['author_email'] = $this->faker->safeEmail;

        $response = $this->call('POST', '/links', $payload, [], $files);
        self::assertTrue($response->isEmpty());
    }
}
