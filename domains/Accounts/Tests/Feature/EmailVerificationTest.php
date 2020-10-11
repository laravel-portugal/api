<?php

namespace Domains\Accounts\Tests\Feature;

use Carbon\Carbon;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->unverified()->create();
        $this->faker = Factory::create();
    }

    /** @test */
    public function it_fails_to_validate_a_users_email_on_link_hash_mismatch(): void
    {
        $this->get(route(
            'accounts.users.verify',
            $this->user->id,
            base64_encode(Hash::make($this->faker->safeEmail))
        ))->assertResponseStatus(Response::HTTP_NOT_FOUND);

        $this->seeInDatabase('users', [
            'id' => $this->user->id,
            'email_verified_at' => null,
        ]);
    }

    /** @test */
    public function it_validates_a_users_email_with_correct_link_hash(): void
    {
        $response = $this->get(route('accounts.users.verify', [
            'id' => $this->user->id,
            'hash' => \base64_encode(Crypt::encrypt($this->user->email)),
        ]));

        $response->assertResponseStatus(Response::HTTP_OK);
        $response->response->assertViewIs('accounts::users.verify-email');

        self::assertInstanceOf(Carbon::class, $this->user->refresh()->email_verified_at);
    }
}
