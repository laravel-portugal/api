<?php

namespace Domains\Discussions\Tests\Feature;

use Carbon\Carbon;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\AnswerFactory;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AnswersIndexControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;
    private Question $question;
    private Answer $answer;
    private Answer $secondAnswer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();

        $this->question = QuestionFactory::new([
            'author_id' => $this->user->id
        ])->create();

        $this->answer = AnswerFactory::new([
            'question_id' => $this->question->id,
            'author_id' => $this->user->id,
            'created_at' => Carbon::now()->subWeek()->toDateTimeString()
        ])->create();

        $this->secondAnswer = AnswerFactory::new([
            'question_id' => $this->question->id,
            'created_at' => Carbon::now()->toDateTimeString()
        ])->create();
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question(): void
    {
        $this->get(route('discussions.questions.answers.list', ['questionId' => $this->question->id]))
            ->seeJsonStructure([
                'data' => [
                    0 => [
                        'id',
                        'content',
                        'question_id',
                        'author_id',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ])
            ->seeJsonContains(['id' => $this->answer->id])
            ->seeJsonContains(['content' => $this->answer->content])
            ->seeJsonContains(['id' => $this->secondAnswer->id])
            ->seeJsonContains(['content' => $this->secondAnswer->content]);
    }

    /** @test * */
    public function it_gets_paginated_answers_for_a_question_from_a_particular_author(): void
    {
        $this->get(route('discussions.questions.answers.list', ['questionId' => $this->question->id, 'author' => $this->user->id]))
            ->seeJson(['id' => $this->answer->id])
            ->dontSeeJson(['id' => $this->secondAnswer->id]);
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question_from_a_particular_time_frame(): void
    {
        $aWeekAgo = Carbon::now()->subDays(8);
        $yesterday = Carbon::yesterday();

        $this->get(route('discussions.questions.answers.list', ['questionId' => $this->question->id]) . '?created[from]=' . $aWeekAgo->format('Y-m-d') . '&created[to]=' . $yesterday->format('Y-m-d'))
            ->seeJsonContains(['id' => $this->answer->id])
            ->seeJsonContains(['content' => $this->answer->content])
            ->seeJsonDoesntContains([
                "id" => $this->secondAnswer->id
            ]);
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question_from_a_particular_time_frame_and_user(): void
    {
        $thirdAnswer = AnswerFactory::new([
            'question_id' => $this->question->id,
            'author_id' => $this->user->id,
            'created_at' => Carbon::now()->subWeek()->toDateTimeString()
        ])->create();

        $aWeekAgo = Carbon::now()->subDays(8);
        $yesterday = Carbon::yesterday();

        $this->get(route('discussions.questions.answers.list', ['questionId' => $this->question->id]) . '?created[from]=' . $aWeekAgo->format('Y-m-d') . '&created[to]=' . $yesterday->format('Y-m-d') . '&author=1')
            ->seeJsonContains(['id' => $this->answer->id])
            ->seeJsonContains(['content' => $this->answer->content])
            ->seeJsonContains(['id' => $thirdAnswer->id])
            ->seeJsonContains(['content' => $thirdAnswer->content])
            ->dontSeeJson([
                "id" => $this->secondAnswer->id
            ]);
    }

    /** @test */
    public function it_blocks_guest_for_many_attempts(): void
    {
        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.answers.list', ['questionId' => $this->question->id]))
                ->assertResponseStatus(Response::HTTP_OK);
        }

        $this->get(route('discussions.questions.answers.list', ['questionId' => $this->question->id]))
            ->assertResponseStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /** @test */
    public function it_not_blocks_authenticated_user_for_many_attempts(): void
    {
        $this->actingAs($this->user);

        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.answers.list', ['questionId' => $this->question->id]));
        }

        $this->get(route('discussions.questions.answers.list', ['questionId' => $this->question->id]))
            ->assertResponseStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_gets_question_and_author_when_loaded(): void
    {
        $answer = AnswerFactory::new([
            'question_id' => $this->question->id,
            'author_id' => $this->user->id,
            'created_at' => Carbon::now()->subWeek()->toDateTimeString()
        ])->create()->load('question', 'author');

        $this->assertArrayHasKey('author', $answer->relationsToArray());
        $this->assertArrayHasKey('question', $answer->relationsToArray());
    }
}
