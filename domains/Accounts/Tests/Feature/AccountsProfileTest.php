<?php

namespace Domains\Accounts\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountsProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected function setUp(): void {
        parent::setUp();
        Artisan::call('passport:install');
        $this->user  = UserFactory::new(['password' => Hash::make('greatpassword')])->create();
    }

    /** @test */
    public function guest_cannot_see_profile(): void {
        $response = $this->get(route('accounts.me'), ['Authorization' => 'Bearer ' . '']);

        $response->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function authenticated_user_can_see_profile(): void {
        $token = $this->user->createToken('Token Test')->accessToken;

        $response = $this->get(route('accounts.me'), ['Authorization' => 'Bearer ' . $token]);

        $response->assertResponseStatus(Response::HTTP_OK);
    }

}
