<?php

namespace Domains\Discussions\Tests\Feature;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Domains\Discussions\Models\Question;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
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
    public function it_updates_questions(): void
    {
        $payload = [
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
        ];

        $this->actingAs($this->user);
        $response = $this->call('PATCH', '/questions/' . $this->question->id, $payload);
        self::assertTrue($response->isEmpty());

        $this->seeInDatabase('questions', [
            'author_id' => $this->user->id,
            'title' => $payload['title'],
            'description' => $payload['description'],
            'updated_at' => Carbon::now()
        ]);
    }

    /** @test */
    public function it_calculates_a_slug(): void
    {
        self::assertEquals(Str::slug($this->question->title), $this->question->slug);
    }

    /** @test */
    public function it_stores_question_with_same_title(): void
    {
        $this->actingAs($this->user);

        $response = $this->call('POST', '/questions', [
            'title' => $this->question->title, // Use same 'title' as the Question created in setUp()
            'description' => $this->question->description,
        ]);

        self::assertTrue($response->isEmpty());
        self::assertEquals(2, Question::query()->count());
    }

    /** @test */
    public function it_forbids_guests_to_store_questions(): void
    {
        $this->post('/questions')
            ->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_fails_to_store_questions_on_validation_errors(): void
    {
        $this->actingAs($this->user);

        $this->post('/questions')
            ->seeJsonStructure([
                'title',
                'description',
            ]);
    }
}
