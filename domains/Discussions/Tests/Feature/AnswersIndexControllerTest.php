<?php

namespace Domains\Discussions\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Tests\TestCase;
use Domains\Accounts\Models\User;
use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Discussions\Database\Factories\AnswerFactory;
use Domains\Discussions\Database\Factories\QuestionFactory;

class AnswersIndexControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;
    private User $secondUser;
    private Question $question;
    private Answer $answer;
    private Answer $secondAnswer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->secondUser = UserFactory::new()->create();

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
            'author_id' => $this->secondUser->id,
            'created_at' => Carbon::now()->toDateTimeString()
        ])->create();
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question(): void
    {
        $this->get(route('discussions.questions.answersList', ['questionId' => $this->question->id]))
            ->seeJsonStructure([
                "data" => [
                    0 => [
                        "id",
                        "content",
                        "question" => [
                            "id",
                            "title",
                            "slug",
                            "description",
                            "author" => [
                                "id",
                                "name",
                                "email",
                                "trusted",
                                "created_at",
                                "updated_at",
                                "deleted_at",
                            ],
                            "created_at",
                            "updated_at",
                            "resolved_at",
                            "deleted_at",
                        ],
                        "author" => [
                            "id",
                            "name",
                            "email",
                            "trusted",
                            "created_at",
                            "updated_at",
                            "deleted_at",
                        ],
                        "created_at",
                        "updated_at",
                        "deleted_at",
                    ]
                ]
            ])
            // answer 1
        ->seeJsonContains(['id' => $this->answer->id])
        ->seeJsonContains(['content' => $this->answer->content])
            // answer 2
        ->seeJsonContains(['id' => $this->secondAnswer->id])
        ->seeJsonContains(['content' => $this->secondAnswer->content]);
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question_from_a_particular_author(): void
    {
        $this->get(route('discussions.questions.answersList', ['questionId' => $this->question->id, 'author' => $this->user->id]))
            ->seeJson(['id' => $this->answer->id])
            ->dontSeeJson(['id' => $this->secondAnswer->id]);
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question_from_a_particular_time_frame(): void
    {
        $aWeekAgo = Carbon::now()->subDays(8);
        $yesterday = Carbon::yesterday();

        $this->get(route('discussions.questions.answersList', ['questionId' => $this->question->id]) . '?created[from]=' . $aWeekAgo->format('Y-m-d') . '&created[to]=' . $yesterday->format('Y-m-d'))
            // answer 1
            ->seeJsonContains(['id' => $this->answer->id])
            ->seeJsonContains(['content' => $this->answer->content])
            // answer 2
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

        $this->get(route('discussions.questions.answersList', ['questionId' => $this->question->id]) . '?created[from]=' . $aWeekAgo->format('Y-m-d') . '&created[to]=' . $yesterday->format('Y-m-d') . '&author=1')
            // answer 1
            ->seeJsonContains(['id' => $this->answer->id])
            ->seeJsonContains(['content' => $this->answer->content])
            // answer 3
            ->seeJsonContains(['id' => $thirdAnswer->id])
            ->seeJsonContains(['content' => $thirdAnswer->content])
            // answer 2
            ->dontSeeJson([
                "id" => $this->secondAnswer->id
            ]);
    }

    /** @test */
    public function it_blocks_guest_for_many_attempts(): void
    {
        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.answersList', ['questionId' => $this->question->id]))
                ->assertResponseStatus(Response::HTTP_OK);
        }

        $this->get(route('discussions.questions.answersList', ['questionId' => $this->question->id]))
            ->assertResponseStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /** @test */
    public function it_not_blocks_authenticated_user_for_many_attempts(): void
    {
        $this->actingAs($this->user);

        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.answersList', ['questionId' => $this->question->id]));
        }

        $this->get(route('discussions.questions.answersList', ['questionId' => $this->question->id]))
            ->assertResponseStatus(Response::HTTP_OK);
    }
}
