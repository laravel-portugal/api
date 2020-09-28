<?php

namespace Domains\Links\Tests\Feature;

use Domains\Links\Database\Factories\LinkFactory;
use Domains\Links\Exceptions\UnapprovedLinkLimitReachedException;
use Domains\Links\Models\Link;
use Faker\Factory;
use Faker\Generator;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class LinksStoreLimitTest extends TestCase
{
    use DatabaseMigrations;

    protected Generator $faker;
    protected string $authorEmail;
    protected int $limit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->authorEmail = $this->faker->safeEmail;

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
        $this->expectException(UnapprovedLinkLimitReachedException::class);

        // use the same authorEmail as in the setUp(), this time going over the allowed limit
        LinkFactory::new()
            ->withAuthorEmail($this->authorEmail)
            ->create();

        $this->assertEquals($this->limit, Link::count());
    }

    /** @test */
    public function it_stores_resources_above_unapproved_limit_when_from_another_author(): void
    {
        // use a random author_email
        LinkFactory::new()
            ->withAuthorEmail($this->faker->safeEmail)
            ->create();

        $this->assertEquals($this->limit + 1, Link::count());
    }
}
