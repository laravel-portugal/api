<?php

namespace Domains\Discussions\Tests\Feature;

use Carbon\Carbon;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class QuestionsIndexTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function invalidSearchablePropertiesValuesProvider(): array
    {
        return [
            'Search author by string' => [
                ['author' => 'author'],
            ],
            'Search resolved with int' => [
                ['resolved' => 21333],
            ],
            'Search create only from date' => [
                ['created[from]' => Carbon::now()->subMonth()->subYears(2)->toDateString()],
            ],
            'Search with a "to" date less than "from"' => [
                [
                    'created[from]' => Carbon::now()->subMonth()->subYears(2)->toDateString(),
                    'created[to]' => Carbon::now()->subMonth()->subYears(3)->toDateString(),
                ],
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        QuestionFactory::times(10)->create();
    }

    /** @test */
    public function it_list_non_deleted_question(): void
    {
        $deleteQuestion = QuestionFactory::new([
            'deleted_at' => Carbon::now(),
        ])
            ->create();

        $this->json('GET', route('discussions.questions.index'))
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
                        'resolved_at',
                        'deleted_at',
                    ],
                ],
                'links' => [
                    'first', 'prev', 'next', 'last',
                ],
            ])
            ->seeJsonDoesntContains([
                'email' => $deleteQuestion->author->email,
            ])
            ->seeJsonContains([
                'to' => 10,
            ])
            ->assertResponseOk();
    }

    /** @test */
    public function it_blocks_guest_for_many_attempts(): void
    {
        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.index'))
                ->assertResponseStatus(Response::HTTP_OK);
        }

        $this->get(route('discussions.questions.index'))
            ->assertResponseStatus(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /** @test */
    public function it_not_blocks_authenticated_user_for_many_attempts(): void
    {
        $this->actingAs($this->user);

        for ($attempt = 0; $attempt < 30; ++$attempt) {
            $this->get(route('discussions.questions.index'));
        }

        $this->get(route('discussions.questions.index'))
            ->assertResponseStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_navigates_to_next_page(): void
    {
        $this->json('GET', route('discussions.questions.index', [
            'page' => 2,
        ]))
            ->seeJsonContains([
                'current_page' => 2,
            ]);
    }

    /** @test */
    public function it_searches_by_author(): void
    {
        $user = UserFactory::new()->create();

        QuestionFactory::new([
            'author_id' => $user->id,
        ])
            ->count(3)
            ->create();

        $this->json('GET', route('discussions.questions.index', [
            'author' => $user->id,
        ]))
            ->seeJsonContains([
                'email' => $user->email,
            ])
            ->seeJsonContains([
                'to' => 3,
            ]);
    }

    /** @test */
    public function it_searches_by_title(): void
    {
        QuestionFactory::new([
            'title' => 'LARAVEL-PT',
        ])
            ->create();

        QuestionFactory::new([
            'title' => 'laravel-Pt',
        ])
            ->create();

        $this->json('GET', route('discussions.questions.index', [
            'title' => 'LArAvEL-pT',
        ]))
            ->seeJsonContains([
                'to' => 2,
            ]);
    }

    /** @test */
    public function it_searches_by_created_date(): void
    {
        QuestionFactory::new([
            'created_at' => Carbon::now()->subYears(2),
        ])
            ->create();

        QuestionFactory::new([
            'created_at' => Carbon::now()->subYears(3),
        ])
            ->create();

        $this->json('GET', route('discussions.questions.index', [
            'created[from]' => Carbon::now()->subMonth()->subYears(2)->toDateString(),
            'created[to]' => Carbon::now()->addMonth()->subYears(2)->toDateString(),
        ]))
            ->seeJsonContains([
                'to' => 1,
            ]);

        $this->json('GET', route('discussions.questions.index', [
            'created[from]' => Carbon::now()->subMonth()->subYears(3)->toDateString(),
            'created[to]' => Carbon::now()->addMonth()->subYears(2)->toDateString(),
        ]))
            ->seeJsonContains([
                'to' => 2,
            ]);
    }

    /** @test */
    public function it_searches_by_resolved_flag(): void
    {
        $questionsResolved = QuestionFactory::new([
            'resolved_at' => Carbon::now(),
        ])
            ->create();

        $this->json('GET', route('discussions.questions.index', [
            'resolved' => true,
        ]))
            ->seeJsonContains([
                'resolved_at' => $questionsResolved->resolved_at,
            ])
            ->seeJsonDoesntContains([
                'resolved_at' => null,
            ]);

        $this->json('GET', route('discussions.questions.index', [
            'resolved' => false,
        ]))
            ->seeJsonDoesntContains([
                'resolved_at' => $questionsResolved->resolved_at,
            ])
            ->seeJsonContains([
                'resolved_at' => null,
            ]);
    }

    /**
     * @test
     * @dataProvider invalidSearchablePropertiesValuesProvider
     *
     * @param array $searchParamAndValue
     */
    public function it_fails_search_if_given_invalid_search_params_values(array $searchParamAndValue): void
    {
        $this->get(route('discussions.questions.index', $searchParamAndValue))
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY, );
    }
}
