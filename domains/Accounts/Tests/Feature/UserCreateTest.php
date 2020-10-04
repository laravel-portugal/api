<?php

namespace Domains\Accounts\Tests\Feature;

use Domains\Links\Database\Factories\LinkFactory;
use Domains\Links\Models\Link;
use Faker\Factory;
use Faker\Generator;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    use DatabaseMigrations;

    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    /** @test */
    public function it_fails_to_create_a_user_on_validation_errors(): void
    {

        $this->post(route('accounts.store'), [])
            ->seeJsonStructure([
                'name',
                'email',
                'password',
            ]);
    }

    /** @test */
    public function it_creates_a_user(): void
    {
        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
        ];

        $response = $this->call('POST', route('accounts.store'), $payload);

        self::assertTrue($response->isEmpty());

        $this->seeInDatabase('users', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'email_verified_at' => null,
        ]);
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
