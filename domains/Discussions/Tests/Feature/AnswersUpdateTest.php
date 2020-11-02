<?php

namespace Domains\Discussions\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\AnswerFactory;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class AnswersUpdateTest extends TestCase
{
    use DatabaseMigrations;

    private Generator $faker;
    private User $user;
    private Question $question;
    private Answer $answer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker    = Factory::create();
        $this->user     = UserFactory::new()->create();
        $this->question = QuestionFactory::new(['author_id' => $this->user->id])->create();
        $this->answer   = AnswerFactory::new([
            'author_id' => $this->user->id,
            'question_id' => $this->question->id])->create();
    }

    /** @test */
    public function it_updates_answer(): void
    {
        $payload = [
            'content' => $this->faker->paragraph,
        ];

        $response = $this->actingAs($this->user)
            ->call('PATCH', route('discussions.questions.answers.update', ['questionId' => $this->question->id, 'answerId' => $this->answer->id]), $payload)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        self::assertTrue($response->isEmpty());

        $this->seeInDatabase('question_answers', [
            'author_id' => $this->user->id,
            'question_id' => $this->question->id,
            'content' => $payload['content']
        ]);
    }

    /** @test */
    public function it_forbids_guests_to_update_answer(): void
    {
        $this->patch(route('discussions.questions.answers.update', ['questionId' => $this->question->id, 'answerId' => $this->answer->id]))
            ->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_fails_to_update_answer_on_validation_errors(): void
    {
        $this->actingAs($this->user)
            ->patch(route('discussions.questions.answers.update', ['questionId' => $this->question->id, 'answerId' => $this->answer->id]))
            ->seeJsonStructure([
                'content',
            ]);
    }

    /** @test */
    public function it_fails_to_update_answer_on_invalid_question(): void
    {
        $payload = [
            'content' => $this->faker->paragraph,
        ];

        $this->actingAs($this->user)
            ->post(route('discussions.questions.answers.update', ['questionId' => 1000, 'answerId' => $this->answer->id]), $payload)
            ->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_fails_to_update_answer_on_invalid_answer(): void
    {
        $payload = [
            'content' => $this->faker->paragraph,
        ];

        $this->actingAs($this->user)
            ->post(route('discussions.questions.answers.update', ['questionId' => $this->question->id, 'answerId' => 1000]), $payload)
            ->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }
}
