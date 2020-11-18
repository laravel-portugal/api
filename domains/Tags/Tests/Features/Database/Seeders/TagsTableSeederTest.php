<?php

namespace Domains\Tags\Tests\Features\Database\Seeders;

use Domains\Tags\Database\Seeders\TagsTableSeeder;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class TagsTableSeederTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_seeds_tags_table(): void
    {
        $this->artisan('db:seed', ['--class' => TagsTableSeeder::class]);

        $this->seeInDatabase('tags', ['name' => 'Eloquent'])
            ->seeInDatabase('tags', ['name' => 'Livewire'])
            ->seeInDatabase('tags', ['name' => 'Vue'])
            ->seeInDatabase('tags', ['name' => 'Testing']);
    }
}
