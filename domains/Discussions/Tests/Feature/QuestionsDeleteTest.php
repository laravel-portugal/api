<?php

namespace Domains\Discussions\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Enums\AccountTypeEnum;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Domains\Discussions\Models\Question;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class QuestionsUpdateTest extends TestCase
{
    use DatabaseMigrations;

    private Generator $faker;
    private User $user;
    private Question $question;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->user = UserFactory::new()->create();
        $this->question = QuestionFactory::new(['author_id' => $this->user->id])->create();
    }

    /** @test */
    public function it_soft_deletes_a_question_i_own(): void
    {
        Carbon::setTestNow();

        $response = $this->actingAs($this->user)
            ->delete(route('discussions.questions.delete', ['questionId' => $this->question->id]));

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
        $this->assertTrue($response->response->isEmpty());
        $this->seeInDatabase('questions', [
            'id' => $this->question->id,
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ]);
    }

    /** @test */
    public function it_allows_admin_to_soft_delete_another_users_question(): void
    {
        $response = $this->actingAs(UserFactory::new()->withRole(AccountTypeEnum::ADMIN)->make())
            ->delete(route('discussions.questions.update', ['questionId' => $this->question->id]));

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
        $this->assertTrue($response->response->isEmpty());
        $this->seeInDatabase('questions', [
            'id' => $this->question->id,
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ]);
    }

    /** @test */
    public function it_forbids_a_non_admin_to_soft_delete_a_question_he_doesnt_own(): void
    {
        $this->actingAs(UserFactory::new()->make())
            ->delete(route('discussions.questions.update', ['questionId' => $this->question->id]));

        $this->assertResponseStatus(Response::HTTP_FORBIDDEN);
        $this->seeInDatabase('questions', [
            'id' => $this->question->id,
            'updated_at' => $this->question->updated_at,
            'deleted_at' => null,
        ]);
    }
}
