<?php

namespace Domains\Tags\Tests\Features;

use Domains\Tags\Database\Factories\TagFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class TagsIndexTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        TagFactory::times(2)->create();
    }

    /** @test */
    public function it_lists_all_resources(): void
    {
        $response = $this->get('/tags')
            ->seeJsonStructure([
                'data' => [
                    [
                        'id', 'name', 'created_at',
                    ],
                ],
            ]);

        $response->assertResponseOk();

        self::assertCount(2, $response->decodedJsonResponse()['data']);
    }
}
