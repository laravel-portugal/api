<?php

namespace Domains\Accounts\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountsProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->create();
    }

    /** @test */
    public function guest_cannot_see_profile(): void
    {
        $response = $this->get(route('accounts.me'), ['Authorization' => 'Bearer ']);

        $response->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function authenticated_user_can_see_profile(): void
    {
        $this->actingAs($this->user)
            ->get(route('accounts.me'))
            ->seeJson([
                    "id" => $this->user->id,
                    "name" => $this->user->name,
                    "email" => $this->user->email,
                    "trusted" => $this->user->trusted,
                    "created_at" => $this->user->created_at,
                    "updated_at" => $this->user->updated_at,
                    "deleted_at" => $this->user->deleted_at
            ])
            ->assertResponseStatus(Response::HTTP_CREATED);
    }
}
