<?php

namespace Domains\Accounts\Tests\Feature;

use Carbon\Carbon;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
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
        $this->get(route('accounts.verify',
            $this->user->id,
            base64_encode(Hash::make($this->faker->safeEmail))
        ))->assertResponseStatus(Response::HTTP_FORBIDDEN);

        $this->seeInDatabase($this->user->getTable(), [
            'id' => $this->user->id,
            'email_verified_at' => null,
        ]);
    }

    /** @test */
    public function it_validates_a_users_email_with_correct_link_hash(): void
    {
        $this->get(route('accounts.verify', [
            'id' => $this->user->id,
            'hash' => base64_encode(Hash::make($this->user->email))
        ]))->assertResponseStatus(Response::HTTP_NO_CONTENT);

        $this->seeInDatabase($this->user->getTable(), [
            'id' => $this->user->id,
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
