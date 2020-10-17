<?php

namespace Domains\Accounts\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountsLoginTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
        $this->user  = UserFactory::new(['password' => Hash::make('greatpassword')])->create();
        $this->faker = Factory::create();
    }

    /** @test */
    public function it_fails_to_login_on_validation_errors(): void
    {
        $response = $this->post(route('accounts.login'), [
            'email' => $this->faker->safeEmail
        ]);

        $response->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function guest_fail_login_with_not_exist_user(): void
    {
        $response = $this->post(route('accounts.login'), [
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password]);

        $response->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function guest_fail_login_with_wrong_credential(): void
    {
        $response = $this->post(route('accounts.login'), [
            'email' => $this->user->email,
            'password' => $this->faker->password
        ]);

        $response->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function guest_blocked_for_many_attempts(): void
    {
        for ($attempt = 0; $attempt < 10; ++$attempt) {
            $this->post(route('accounts.login'), [
                'email' => $this->user->email,
                'password' => $this->faker->password
            ]);
        }

        $response = $this->post(route('accounts.login'), [
            'email' => $this->user->email,
            'password' => $this->faker->password
        ]);

        $response->assertResponseStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /** @test */
    public function guest_can_make_login_with_correct_credential(): void
    {
        $this->post(route('accounts.login'), [
            'email' => $this->user->email,
            'password' => 'greatpassword'
        ])
            ->seeJsonStructure(['access_token'])
            ->assertResponseStatus(Response::HTTP_OK);

        $countTokensAfter = DB::table('oauth_access_tokens')->count();

        $this->assertEquals($countTokensAfter, 1);

    }
}
