<?php

namespace Domains\Discussions\Tests\Feature;

use Carbon\Carbon;
use Faker\Factory;
use Tests\TestCase;
use Faker\Generator;
use Illuminate\Http\Response;
use Domains\Accounts\Models\User;
use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Discussions\Database\Factories\AnswerFactory;
use Domains\Discussions\Database\Factories\QuestionFactory;

class QuestionsGetAnswersTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;
    private User $secondUser;
    private Question $question;
    private Answer $answer;
    private Answer $secondAnswer;
    private Answer $thirdAnswer;

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

        $this->thirdAnswer = AnswerFactory::new([
            'question_id' => $this->question->id,
            'author_id' => $this->user->id,
            'created_at' => Carbon::now()->subWeek()->toDateTimeString()
        ])->create();
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question(): void
    {
        $this->get(route('discussions.questions.answers', ['id' => $this->question->id]))
            ->seeJson([
                "id" => $this->answer->id,
                "question_id" => '' . $this->question->id
            ])
            ->seeJson([
                "id" => $this->secondAnswer->id,
                "question_id" => '' . $this->question->id
            ]);
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question_from_a_particular_author(): void
    {
        $this->get(route('discussions.questions.answers', ['id' => $this->question->id, 'author' => $this->user->id]))
            ->seeJson(['id' => $this->answer->id])
            ->dontSeeJson(['id' => $this->secondAnswer->id]);
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question_from_a_particular_time_frame(): void
    {
        $aWeekAgo = Carbon::now()->subDays(8);
        $yesterday = Carbon::yesterday();

        $this->get(route('discussions.questions.answers', ['id' => $this->question->id]) . '?created[from]=' . $aWeekAgo->format('Y-m-d') . '&created[to]=' . $yesterday->format('Y-m-d'))
            ->seeJson([
                "id" => $this->answer->id,
                "question_id" => '' . $this->question->id
            ])
            ->dontSeeJson([
                "id" => $this->secondAnswer->id
            ]);
    }

    /** @test */
    public function it_gets_paginated_answers_for_a_question_from_a_particular_time_frame_and_user(): void
    {
        $aWeekAgo = Carbon::now()->subDays(8);
        $yesterday = Carbon::yesterday();

        $this->get(route('discussions.questions.answers', ['id' => $this->question->id]) . '?created[from]=' . $aWeekAgo->format('Y-m-d') . '&created[to]=' . $yesterday->format('Y-m-d') . '&author=1')
            ->seeJson([
                "id" => $this->answer->id,
                "question_id" => '' . $this->question->id
            ])
            ->seeJson([
                "id" => $this->thirdAnswer->id,
            ])
            ->dontSeeJson([
                "id" => $this->secondAnswer->id
            ]);
    }
}
