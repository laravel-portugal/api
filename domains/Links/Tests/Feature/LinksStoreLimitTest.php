<?php

namespace Domains\Links\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Links\Database\Factories\LinkFactory;
use Domains\Links\Models\Link;
use Domains\Tags\Database\Factories\TagFactory;
use Domains\Tags\Models\Tag;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class LinksStoreLimitTest extends TestCase
{
    use DatabaseMigrations;

    protected Generator $faker;
    protected string $authorEmail;
    protected Tag $tag;
    protected int $limit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->authorEmail = $this->faker->safeEmail;
        $this->tag = TagFactory::new()->create();

        // set a random limit
        $this->limit = rand(5, 20);
        config(['links.max_unapproved_links' => $this->limit]);

        // create $limit number of Links
        LinkFactory::times($this->limit)
            ->withAuthorEmail($this->authorEmail)
            ->create();
    }

    /** @test */
    public function it_fails_to_store_resources_when_exceeding_unapproved_limit(): void
    {
        Storage::fake('local');

        // use the same authorEmail as in the setUp(), this time going over the allowed limit
        $payload = [
            'link' => $this->faker->url,
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'author_name' => $this->faker->name,
            'author_email' => $this->authorEmail,
            'tags' => [
                ['id' => $this->tag->id],
            ],
        ];

        $files = [
            'cover_image' => UploadedFile::fake()->image('cover_image.jpg'),
        ];

        $response = $this->call('POST', route('links.store'), $payload, [], $files);

        $this->assertEquals(Response::HTTP_TOO_MANY_REQUESTS, $response->getStatusCode());
        $this->assertEquals($this->limit, Link::count());
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

        $response = $this->call('POST', route('links.store'), $payload, [], $files);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals($this->limit + 1, Link::count());
    }
}
