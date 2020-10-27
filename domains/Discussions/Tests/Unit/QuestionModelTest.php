<?php

namespace Domains\Discussions\Tests\Unit;

use Carbon\Carbon;
use Domains\Accounts\Models\User;
use Domains\Discussions\Database\Factories\QuestionFactory;
use Domains\Discussions\Models\Question;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class QuestionModelTest extends TestCase
{
    use DatabaseMigrations;

    private Question $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = QuestionFactory::new()->make();
    }

    /** @test */
    public function it_contains_required_properties(): void
    {
        self::assertIsInt($this->model->author_id);
        self::assertIsString($this->model->title);
        self::assertIsString($this->model->description);
        self::assertNull($this->model->slug);
        self::assertNull($this->model->resolved_at);
        self::assertInstanceOf(Carbon::class, $this->model->created_at);
        self::assertInstanceOf(Carbon::class, $this->model->updated_at);
    }

    /** @test */
    public function it_uses_correct_table_name(): void
    {
        self::assertEquals('questions', $this->model->getTable());
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
}
