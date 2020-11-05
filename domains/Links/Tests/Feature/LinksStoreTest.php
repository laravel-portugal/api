<?php

namespace Domains\Links\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
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

    private array $payload;
    private array $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tag = TagFactory::new()->create();
        $this->faker = Factory::create();

        $this->payload = [
            'link' => $this->faker->url,
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'author_name' => $this->faker->name,
            'author_email' => $this->faker->safeEmail,
            'tags' => [
                ['id' => $this->tag->id],
            ],
        ];
        $this->files = [
            'cover_image' => UploadedFile::fake()->image('cover_image.jpg'),
        ];

        Storage::fake('local');
    }

    /** @test */
    public function it_stores_resources(): void
    {
        $response = $this->call('POST', '/links', $this->payload, [], $this->files);
        self::assertTrue($response->isEmpty());

        $this->seeInDatabase('links', [
            'link' => $this->payload['link'],
            'title' => $this->payload['title'],
            'description' => $this->payload['description'],
            'author_name' => $this->payload['author_name'],
            'author_email' => $this->payload['author_email'],
            'cover_image' => 'cover_images/' . $this->files['cover_image']->hashName(),
            'approved_at' => null,
        ]);

        $this->seeInDatabase(
            'link_tag',
            [
                'tag_id' => $this->tag->id,
            ]
        );

        Storage::assertExists('cover_images/' . $this->files['cover_image']->hashName());
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
        $this->payload['link'] = 'this_is_not_a_valid_url';

        $this->post('/links', $this->payload)
            ->seeJsonStructure([
                'link',
            ]);
    }

    /** @test */
    public function it_stores_resources_with_unregistered_link_domain(): void
    {
        $this->payload['link'] = 'http://unregistered.laravel.pt';

        $response = $this->call('POST', '/links', $this->payload, [], $this->files);

        self::assertEquals(204, $response->getStatusCode());
        self::assertTrue($response->isEmpty());
    }

    /** @test */
    public function it_forbids_guest_to_use_a_registered_users_email_when_submitting_a_link(): void
    {
        $user = UserFactory::new(['email' => $this->faker->safeEmail])
            ->create();

        $this->payload['author_email'] = $user->email;

        $this->post('/links', $this->payload)
            ->seeJsonStructure(['author_email']);
    }

    /** @test */
    public function it_uses_logged_in_user_email_and_name_when_submitting_a_link(): void
    {
        // create a random user
        $randomUser = UserFactory::new(['email' => $this->faker->safeEmail])->create();
        // create a user and login
        $user = UserFactory::new(['email' => $this->faker->safeEmail])->create();
        $this->actingAs($user);

        // use an existing user's email and it should go OK since we're logged in.
        $this->payload['author_email'] = $randomUser->email;

        $response = $this->call('POST', '/links', $this->payload, [], $this->files);

        self::assertEquals(204, $response->getStatusCode());
        $this->seeInDatabase(
            'links',
            [
                'author_email' => $user->email,
                'author_name' => $user->name,
            ]
        );
    }
}
