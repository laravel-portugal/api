<?php

namespace Domains\Links\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Enums\AccountTypeEnum;
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
    protected array $payload;
    protected array $files;

    public function unrestrictedUserRolesProvider(): array
    {
        return [
            'Editor Role' => [AccountTypeEnum::EDITOR],
            'Admin Role' => [AccountTypeEnum::ADMIN],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->faker = Factory::create();
        $this->authorEmail = $this->faker->safeEmail;
        $this->tag = TagFactory::new()->create();

        // set a random limit
        $this->limit = $this->faker->numberBetween(1, 10);
        config(['links.max_unapproved_links' => $this->limit]);

        // create $limit number of Links
        LinkFactory::times($this->limit)
            ->withAuthorEmail($this->authorEmail)
            ->create();

        // prepare the requests' payload and files
        $this->payload = [
            'link' => $this->faker->url,
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'author_name' => $this->faker->name,
            'author_email' => $this->authorEmail,
            'tags' => [
                ['id' => $this->tag->id],
            ],
        ];

        $this->files = [
            'cover_image' => UploadedFile::fake()->image('cover_image.jpg'),
        ];
    }

    /** @test */
    public function it_fails_to_store_links_when_exceeding_unapproved_limit(): void
    {
        $response = $this->call('POST', route('links.store'), $this->payload, [], $this->files);

        self::assertEquals(Response::HTTP_TOO_MANY_REQUESTS, $response->getStatusCode());
        self::assertEquals($this->limit, Link::count());
    }

    /** @test */
    public function it_stores_links_above_unapproved_limit_when_from_another_author(): void
    {
        // use another author_email
        $this->payload['author_email'] = $this->faker->safeEmail;

        $response = $this->call('POST', route('links.store'), $this->payload, [], $this->files);

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        self::assertEquals($this->limit + 1, Link::count());
    }

    /** @test */
    public function it_stores_links_above_unapproved_limit_when_user_is_trusted(): void
    {
        $response = $this->actingAs(UserFactory::new()->trusted()->make())
            ->call('POST', route('links.store'), $this->payload, [], $this->files);

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        self::assertEquals($this->limit + 1, Link::count());
    }

    /**
     * @test
     * @dataProvider unrestrictedUserRolesProvider
     *
     * @param string $role
     */
    public function it_stores_links_above_unapproved_limit_when_user_has_unrestricted_role(string $role): void
    {
        $response = $this->actingAs(UserFactory::new()->withRole($role)->make())
            ->call('POST', route('links.store'), $this->payload, [], $this->files);

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        self::assertEquals($this->limit + 1, Link::count());
    }
}
