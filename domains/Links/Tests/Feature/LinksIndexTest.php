<?php

namespace Domains\Links\Tests\Feature;

use Domains\Links\Database\Factories\LinkFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class LinksIndexTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        LinkFactory::times(20)->approved()->create();
    }

    /** @test */
    public function it_lists_resources(): void
    {
        $this->get('/links')
            ->seeJsonStructure([
                'data' => [
                    [
                        'id',
                        'link',
                        'title',
                        'description',
                        'cover_image',
                        'author_name',
                        'author_email',
                        'created_at',
                    ],
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
            ])
            ->assertResponseOk();
    }

    /** @test */
    public function it_includes_tags_relation(): void
    {
        $response = $this->get('/links?include=tags')
            ->seeJsonStructure([
                'data' => [
                    [
                        'tags',
                    ],
                ],
            ]);

        $response->assertResponseOk();

        self::assertCount(1, $response->decodedJsonResponse()['data'][0]['tags']);
    }

    /** @test */
    public function it_doesnt_include_relations_if_not_required(): void
    {
        $response = $this->get('/links');

        $response->assertResponseOk();

        self::assertArrayNotHasKey('tags', $response->decodedJsonResponse()['data'][0]);
    }

    /** @test */
    public function it_supports_pagination_navigation(): void
    {
        $response = $this->get('/links?page=2');

        $response->assertResponseOk();

        self::assertEquals(2, $response->decodedJsonResponse()['meta']['current_page']);
    }
}
