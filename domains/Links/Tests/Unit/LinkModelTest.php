<?php

namespace Domains\Links\Tests\Unit;

use Carbon\Carbon;
use Domains\Links\Database\Factories\LinkFactory;
use Domains\Links\Models\Link;
use Domains\Tags\Models\Tag;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tests\TestCase;

class LinkModelTest extends TestCase
{
    private Link $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = LinkFactory::new()->make();
    }

    /** @test */
    public function it_contains_required_properties(): void
    {
        self::assertIsString($this->model->link);
        self::assertIsString($this->model->title);
        self::assertIsString($this->model->description);
        self::assertIsString($this->model->cover_image);
        self::assertIsString($this->model->author_name);
        self::assertIsString($this->model->author_email);

        self::assertNull($this->model->approved_at);

        self::assertInstanceOf(Carbon::class, $this->model->created_at);
    }

    /** @test */
    public function it_uses_correct_table_name(): void
    {
        self::assertEquals('links', $this->model->getTable());
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
    public function it_has_tags_relation(): void
    {
        self::assertInstanceOf(Tag::class, $this->model->tags()->getModel());
    }

    /** @test */
    public function it_has_approved_scope(): void
    {
        self::assertTrue(method_exists($this->model, 'scopeApproved'));
    }
}
