<?php

namespace Domains\Links\Tests\Feature\Database\Seeders;

use Domains\Links\Database\Seeders\LinksTableSeeder;
use Domains\Links\Models\Link;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class LinksTableSeederTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_seeds_links_table(): void
    {
        $this->artisan('db:seed', ['--class' => LinksTableSeeder::class]);

        self::assertEquals(20, Link::count());
    }
}
