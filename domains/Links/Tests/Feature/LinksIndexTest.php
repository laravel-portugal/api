<?php

namespace Domains\Links\Tests\Feature;

use Domains\Links\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinksIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        factory(Link::class, 20)->state('approved')->create();
    }

    /** @test */
    public function it_lists_resources(): void
    {
        $this->getJson('/links')
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    [
                        'id', 'link', 'description', 'cover_image', 'author_name', 'author_email', 'created_at',
                    ],
                ],
                'links' => [
                    'first', 'last', 'prev', 'next',
                ],
            ]);
    }

    /** @test */
    public function it_includes_tags_relation(): void
    {
        $this->getJson('/links?include=tags')
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    [
                        'tags',
                    ],
                ],
            ])
            ->assertJsonCount(1, 'data.0.tags');
    }

    /** @test */
    public function it_doesnt_include_relations_if_not_required(): void
    {
        $response = $this->getJson('/links')
            ->assertSuccessful()
            ->decodeResponseJson();

        $this->assertArrayNotHasKey('tags', $response['data'][0]);
    }

    /** @test */
    public function it_supports_pagination_navigation(): void
    {
        $this->getJson('/links?page=2')
            ->assertSuccessful()
            ->assertJsonPath('meta.current_page', 2);
    }
}
