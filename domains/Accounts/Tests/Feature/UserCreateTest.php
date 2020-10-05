<?php

namespace Domains\Accounts\Tests\Feature;

use Domains\Accounts\Models\User;
use Domains\Accounts\Notifications\VerifyEmailNotification;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
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
        Notification::fake();
    }

    /** @test */
    public function it_fails_to_create_a_user_on_validation_errors(): void
    {
        $this->post(route('accounts.store'), [])
            ->seeJsonStructure([
                'name',
                'email',
                'password',
            ])->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        Notification::assertNothingSent();
    }

    /** @test */
    public function it_creates_a_user_with_pending_email_verification(): void
    {
        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
        ];

        $response = $this->post(route('accounts.store'), $payload);

        self::assertTrue($response->response->isEmpty());

        $this->seeInDatabase('users', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'email_verified_at' => null,
        ]);

        Notification::assertSentTo(User::firstOrFail(), VerifyEmailNotification::class);
    }
}
