<?php

namespace Domains\Accounts\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountsLogoutTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected function setUp(): void {
        parent::setUp();
        Artisan::call('passport:install');
        $this->user  = UserFactory::new(['password' => Hash::make('greatpassword')])->create();
    }

    /** @test */
    public function it_fails_to_logout_on_wrong_token(): void {

        $response = $this->post(route('accounts.logout'), [], ['Authorization' => 'Bearer ' . '']);

        $response->assertResponseStatus(Response::HTTP_UNAUTHORIZED);

    }

    /** @test */
    public function authenticated_user_can_make_logout(): void {

        $token = $this->user->createToken('Token Test')->accessToken;

        $response = $this->post(route('accounts.logout'), [], ['Authorization' => 'Bearer ' . $token]);

        $response->assertResponseStatus(Response::HTTP_OK);

    }

}
