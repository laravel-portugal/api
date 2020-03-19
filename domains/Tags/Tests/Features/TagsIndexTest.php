<?php

namespace Domains\Tags\Tests\Features;

use Domains\Tags\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagsIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        factory(Tag::class, 2)->create();
    }

    /** @test */
    public function it_lists_all_resources(): void
    {
        $response = $this->getJson('/tags')
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    [
                        'id', 'name', 'created_at',
                    ],
                ],
            ])
            ->decodeResponseJson();

        $this->assertCount(2, $response['data']);
    }
}
