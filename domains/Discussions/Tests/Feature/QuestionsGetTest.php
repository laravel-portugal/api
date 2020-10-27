<?php

namespace Domains\Discussions\Tests\Feature;

use Carbon\Carbon;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class QuestionsGetTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        QuestionFactory::times(20)->create();
    }

    /** @test */
    public function it_possible_to_see_a_question(): void
    {
        $this->get(route('discussions.questions.index'))
            ->seeJsonStructure([
                'data' => [
                    [
                        'id',
                        'title',
                        'slug',
                        'description',
                        'author',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_blocked_guest_for_many_attempts(): void
    {
        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.index'))
                ->assertResponseStatus(Response::HTTP_OK);
        }

        $this->get(route('discussions.questions.index'))
            ->assertResponseStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /** @test */
    public function it_not_blocked_authenticated_user_for_many_attempts(): void
    {
        $this->actingAs($this->user);

        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.index'));
        }

        $this->get(route('discussions.questions.index'))
            ->assertResponseStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_includes_paginate(): void
    {
        $response = $this->get(route('discussions.questions.index'))
            ->seeJsonStructure([
                'data' =>
                    'links',
            ]);

        $response->assertResponseOk();
    }

    /** @test */
    public function it_supports_pagination_navigation(): void
    {
        $response = $this->get(route('discussions.questions.index', ['page' => 2]));
        $response->assertResponseOk();

        self::assertEquals(2, $response->decodedJsonResponse()['meta']['current_page']);
    }

    /** @test */
    public function it_searchable_by_author(): void
    {
        $user = UserFactory::new()->create();
        QuestionFactory::new(['author_id' => $user->id])->create();

        $response = $this->get(route('discussions.questions.index', ['author' => $user->id]));

        $this->assertEquals(1, $response->decodedJsonResponse()['meta']['total']);
    }

    /** @test */
    public function it_searchable_by_title(): void
    {
        QuestionFactory::new(['title' => 'LARAVEL-PT'])->create();
        QuestionFactory::new(['title' => 'laravel-pt'])->create();

        $response = $this->get(route('discussions.questions.index', ['title' => 'LArAvEL-pt']));

        $this->assertEquals(2, $response->decodedJsonResponse()['meta']['total']);
    }

    /** @test */
    public function it_searchable_by_created_at(): void
    {
        QuestionFactory::new(['created_at' => Carbon::now()->subYears(2)->toDateString()])->create();
        QuestionFactory::new(['created_at' => Carbon::now()->subYears(3)->toDateString()])->create();

        $response = $this->get(route('discussions.questions.index', [
            'created[from]' => Carbon::now()->subMonth()->subYears(2)->toDateString(),
            'created[to]' => Carbon::now()->addMonth()->subYears(2)->toDateString()
        ]));
        $this->assertEquals(1, $response->decodedJsonResponse()['meta']['total']);

        $response = $this->get(route('discussions.questions.index', [
            'created[from]' => Carbon::now()->subMonth()->subYears(3)->toDateString(),
            'created[to]' => Carbon::now()->addMonth()->subYears(2)->toDateString()
        ]));
        $this->assertEquals(2, $response->decodedJsonResponse()['meta']['total']);
    }

    /** @test */
    public function it_searchable_by_resolved(): void
    {
        QuestionFactory::new(['resolved_at' => Carbon::now()->toDateString()])->create();

        $response = $this->get(route('discussions.questions.index', ['resolved' => true]));
        $this->assertEquals(1, $response->decodedJsonResponse()['meta']['total']);
    }

    /** @test */
    public function it_fails_by_validations(): void
    {
        $this->get(route('discussions.questions.index', ['author' => 'author']))
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->get(route('discussions.questions.index', ['resolved' => 12332]))
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->get(route('discussions.questions.index', [
            'created[from]' => Carbon::now()->toDateString(),
        ]))
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->get(route('discussions.questions.index', [
            'created[from]' => Carbon::now()->toDateString(),
            'created[to]' => Carbon::now()->subDay()->toDateString(),
        ]))
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
