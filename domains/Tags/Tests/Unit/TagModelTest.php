<?php

namespace Domains\Tags\Tests\Unit;

use Carbon\Carbon;
use Domains\Tags\Database\Factories\TagFactory;
use Domains\Tags\Models\Tag;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tests\TestCase;

class TagModelTest extends TestCase
{
    private Tag $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = TagFactory::new()->make();
    }

    /** @test */
    public function it_contains_required_properties(): void
    {
        self::assertNotNull($this->model->name);
        self::assertIsString($this->model->name);

        self::assertNotNull($this->model->created_at);
        self::assertInstanceOf(Carbon::class, $this->model->created_at);
    }

    /** @test */
    public function it_uses_correct_table_name(): void
    {
        self::assertEquals('tags', $this->model->getTable());
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
}
