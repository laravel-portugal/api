<?php

namespace Domains\Discussions\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Domains\Discussions\Models\Question;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AnswersStoreTest extends TestCase
{
    use DatabaseMigrations;

    private Generator $faker;
    private User $user;
    private Question $question;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker    = Factory::create();
        $this->user     = UserFactory::new()->create();
        $this->question = QuestionFactory::new(['author_id' => $this->user->id])->create();
    }

    /** @test */
    public function it_stores_answer(): void
    {
        $payload = [
            'content' => $this->faker->paragraph,
        ];

        $response = $this->actingAs($this->user)
            ->call('POST', route('discussions.questions.answers', ['questionId' => $this->question->id]), $payload)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        self::assertTrue($response->isEmpty());

        $this->seeInDatabase('questions_answers', [
            'author_id' => $this->user->id,
            'question_id' => $this->question->id,
            'content' => $payload['content']
        ]);
    }

    /** @test */
    public function it_forbids_guests_to_store_answer(): void
    {
        $this->post(route('discussions.questions.answers', ['questionId' => $this->question->id]))
            ->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_fails_to_store_answer_on_validation_errors(): void
    {
        $this->actingAs($this->user)
            ->post(route('discussions.questions.answers', ['questionId' => $this->question->id]))
            ->seeJsonStructure([
                'content',
            ]);
    }

    /** @test */
    public function it_fails_to_store_answer_on_invalid_question(): void
    {
        $payload = [
            'content' => $this->faker->paragraph,
        ];

        $this->actingAs($this->user)
            ->post(route('discussions.questions.answers', ['questionId' => 1000]), $payload)
            ->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }
}
