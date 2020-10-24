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
        Carbon::setTestNow();

        $payload = [
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
        ];

        $response = $this->actingAs($this->user)
            ->call(
                'PATCH',
                route('discussions.questions.update', ['questionId' => $this->question->id]),
                $payload
            );

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
        $this->assertTrue($response->isEmpty());
        $this->seeInDatabase('questions', [
            'id' => $this->question->id,
            'author_id' => $this->user->id,
            'title' => $payload['title'],
            'slug' => Str::slug($payload['title']),
            'description' => $payload['description'],
            'updated_at' => Carbon::now()
        ]);
    }

    /** @test */
    public function it_fails_to_update_if_title_is_missing(): void
    {
        $this->actingAs($this->user);
        $this->patch(route('discussions.questions.update', ['questionId' => $this->question->id]))
            ->seeJsonStructure([
                'title',
            ]);
    }

    /** @test */
    public function it_keeps_previous_description_if_none_is_sent(): void
    {
        $this->actingAs($this->user);
        $response = $this->call(
            'PATCH',
            route('discussions.questions.update', ['questionId' => $this->question->id]),
            [
                'title' => $this->faker->title,
            ]
        );

        $this->assertTrue($response->isEmpty());
        $this->assertEquals($this->question->description, $this->question->refresh()->description);
    }

    /** @test */
    public function it_forbids_non_owner_to_update_questions(): void
    {
        $this->actingAs(UserFactory::new()->make()) // make another user
            ->patch(
                route('discussions.questions.update', ['questionId' => $this->question->id]),
                [
                    'title' => $this->faker->title,
                ]
            )->assertResponseStatus(Response::HTTP_FORBIDDEN);
    }
}
