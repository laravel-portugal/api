<?php

namespace Domains\Links\Tests\Unit;

use Carbon\Carbon;
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

        $this->model = factory(Link::class)->make();
    }

    /** @test */
    public function it_contains_required_properties(): void
    {
        $this->assertIsString($this->model->link);
        $this->assertIsString($this->model->description);
        $this->assertIsString($this->model->cover_image);
        $this->assertIsString($this->model->author_name);
        $this->assertIsString($this->model->author_email);

        $this->assertNull($this->model->approved_at);

        $this->assertInstanceOf(Carbon::class, $this->model->created_at);
    }

    /** @test */
    public function it_uses_correct_table_name(): void
    {
        $this->assertEquals('links', $this->model->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key(): void
    {
        $this->assertEquals('id', $this->model->getKeyName());
    }

    /** @test */
    public function it_uses_soft_deletes(): void
    {
        $this->assertArrayHasKey(SoftDeletingScope::class, $this->model->getGlobalScopes());
    }

    /** @test */
    public function it_uses_timestamps(): void
    {
        $this->assertTrue($this->model->usesTimestamps());
    }

    /** @test */
    public function it_has_tags_relation(): void
    {
        $this->assertInstanceOf(Tag::class, $this->model->tags()->getModel());
    }

    /** @test */
    public function it_has_approved_scope(): void
    {
        $this->assertTrue(method_exists($this->model, 'scopeApproved'));
    }
}
