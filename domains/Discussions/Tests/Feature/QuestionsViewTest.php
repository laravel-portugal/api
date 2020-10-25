<?php

namespace Domains\Discussions\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Domains\Discussions\Models\Question;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class QuestionsViewTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;
    private Question $question;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user     = UserFactory::new()->create();
        $this->question = QuestionFactory::new(['author_id' => $this->user->id])->create();
    }

    /** @test */
    public function guest_can_see_a_question(): void
    {
        $this->get(route('discussions.questions.view', ['questionId' => $this->question->id]))
        ->seeJson([
            'data' => [
                'id' => $this->question->id,
                'title' => $this->question->title,
                'slug' => $this->question->slug,
                'description' => $this->question->description,
                'author' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'trusted' => $this->user->trusted ? "1" : "0",
                    'created_at' => $this->user->created_at,
                    'updated_at'=> $this->user->updated_at,
                    'deleted_at'=> $this->user->deleted_at
                ],
                'created_at' => $this->question->created_at,
                'updated_at'=> $this->question->updated_at,
                'deleted_at'=> $this->question->deleted_at
            ]
        ]);
    }

    /** @test */
    public function authenticated_user_can_see_a_question(): void
    {
        $this->actingAs($this->user);
        $this->get(route('discussions.questions.view', ['questionId' => $this->question->id]))
            ->seeJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'slug',
                    'description',
                    'author',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]);
    }

    /** @test */
    public function guest_blocked_for_many_attempts(): void
    {
        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.view', ['questionId' => $this->question->id]));
        }

        $this->get(route('discussions.questions.view', ['questionId' => $this->question->id]))
            ->assertResponseStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /** @test */
    public function authenticated_user_is_not_blocked_for_many_attempts(): void
    {
        $this->actingAs($this->user);

        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.view', ['questionId' => $this->question->id]));
        }

        $this->get(route('discussions.questions.view', ['questionId' => $this->question->id]))
            ->assertResponseStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_fails_on_invalid_question(): void
    {
        $this->get(route('discussions.questions.view', ['questionId' => 1000]))
            ->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_fails_on_view_a_deleted_question(): void
    {
        $this->question->delete();
        $this->get(route('discussions.questions.view', ['questionId' => $this->question->id]))
            ->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }
}