<?php

namespace Domains\Tags\Tests\Unit;

use Carbon\Carbon;
use Domains\Tags\Models\Tag;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tests\TestCase;

class TagModelTest extends TestCase
{
    private Tag $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = factory(Tag::class)->make();
    }

    /** @test */
    public function it_contains_required_properties(): void
    {
        $this->assertNotNull($this->model->name);
        $this->assertIsString($this->model->name);

        $this->assertNotNull($this->model->created_at);
        $this->assertInstanceOf(Carbon::class, $this->model->created_at);
    }

    /** @test */
    public function it_uses_correct_table_name(): void
    {
        $this->assertEquals('tags', $this->model->getTable());
    }

    /** @test */
    public function it_uses_soft_deletes(): void
    {
        $this->assertArrayHasKey(SoftDeletingScope::class, $this->model->getGlobalScopes());
    }
}
