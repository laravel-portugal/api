<?php

namespace Domains\Accounts\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class AccountsLoginTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Generator $faker;

    protected function setUp(): void {
        parent::setUp();

        $this->user  = UserFactory::new(['password' => Hash::make('greatpassword')])->create();
        $this->faker = Factory::create();
        Artisan::call('passport:install');
    }

    /** @test */
    public function it_fails_to_login_on_validation_errors(): void {

        $response = $this->post(route('accounts.login'), [
            'email' => $this->faker->safeEmail
        ]);

        $response->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /** @test */
    public function guest_fail_login_with_not_exist_user(): void {

        $response = $this->post(route('accounts.login'), [
            'email'    => $this->faker->safeEmail,
            'password' => $this->faker->password]);

        $response->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function guest_fail_login_with_wrong_credencial(): void {

        $response = $this->post(route('accounts.login'), [
            'email'    => $this->user->email,
            'password' => $this->faker->password
        ]);

        $response->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function guest_blocked_for_many_attempts(): void {

        for ($attemp = 0; $attemp < 10; ++$attemp) {
           $response = $this->post(route('accounts.login'), [
                'email'    => $this->user->email,
                'password' => $this->faker->password
            ]);
            $response->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $response = $this->post(route('accounts.login'), [
            'email'    => $this->user->email,
            'password' => $this->faker->password
        ]);

        $response->assertResponseStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /** @test */
    public function guest_can_make_login_with_correct_credencial(): void {

        $response = $this->post(route('accounts.login'), [
            'email'    => $this->user->email,
            'password' => 'greatpassword'
        ]);
        $response->assertResponseStatus(Response::HTTP_OK);
    }

}
