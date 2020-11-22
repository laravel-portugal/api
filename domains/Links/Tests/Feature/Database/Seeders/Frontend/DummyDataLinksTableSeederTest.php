<?php

namespace Domains\Links\Tests\Feature\Database\Seeders\Frontend;

use Domains\Links\Database\Seeders\Frontend\DummyDataLinksTableSeeder;
use Domains\Links\Models\Link;
use Domains\Tags\Models\Tag;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class DummyDataLinksTableSeederTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_seeds_links_table(): void
    {
        $this->artisan('db:seed', ['--class' => DummyDataLinksTableSeeder::class]);

        self::assertEquals(2, Link::count());
        self::assertEquals(4, Tag::count());
    }
}
