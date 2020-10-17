<?php

namespace Domains\Accounts\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountsLogoutTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->create();
    }

    /** @test */
    public function it_fails_to_logout_on_wrong_token(): void
    {
        $this->post(route('accounts.logout'), [], ['Authorization' => 'Bearer '])
            ->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function authenticated_user_can_make_logout(): void
    {
        $token = auth()->login($this->user);

        $this->get(route('accounts.me'), ['Authorization' => "Bearer {$token}"])
            ->assertResponseStatus(Response::HTTP_OK);

        $this->post(route('accounts.logout'), [], ['Authorization' => "Bearer {$token}"])
            ->assertResponseStatus(Response::HTTP_ACCEPTED);

        $this->get(route('accounts.me'), ['Authorization' => "Bearer {$token}"])
            ->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }
}
