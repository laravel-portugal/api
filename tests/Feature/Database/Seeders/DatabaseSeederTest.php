<?php

namespace Tests\Feature\Database\Seeders;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_prevents_seeds_to_run_in_production(): void
    {
        $this->expectException(\Exception::class);

        $this->app['config']->set('app.env', 'production');

        $this->artisan('db:seed');
    }

    /** @test */
    public function it_can_seed_database(): void
    {
        self::assertEquals(0, $this->artisan('db:seed'));
    }
}
