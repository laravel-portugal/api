<?php

namespace Domains\Discussions\Tests\Unit;

use Carbon\Carbon;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\AnswerFactory;
use Domains\Discussions\Models\Answer;
use Domains\Discussions\Models\Question;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AnswerModelTest extends TestCase
{
    use DatabaseMigrations;

    private Answer $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = AnswerFactory::new()->make();
    }

    /** @test */
    public function it_contains_required_properties(): void
    {
        self::assertIsInt($this->model->author_id);
        self::assertIsInt($this->model->question_id);
        self::assertIsString($this->model->content);
        self::assertInstanceOf(Carbon::class, $this->model->created_at);
        self::assertInstanceOf(Carbon::class, $this->model->updated_at);
    }

    /** @test */
    public function it_uses_correct_table_name(): void
    {
        self::assertEquals('question_answers', $this->model->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key(): void
    {
        self::assertEquals('id', $this->model->getKeyName());
    }

    /** @test */
    public function it_uses_soft_deletes(): void
    {
        self::assertArrayHasKey(SoftDeletingScope::class, $this->model->getGlobalScopes());
    }

    /** @test */
    public function it_uses_timestamps(): void
    {
        self::assertTrue($this->model->usesTimestamps());
    }

    /** @test */
    public function it_has_author_relation(): void
    {
        self::assertInstanceOf(User::class, $this->model->author()->getModel());
    }

    /** @test */
    public function it_has_question_relation(): void
    {
        self::assertInstanceOf(Question::class, $this->model->question()->getModel());
    }
}
