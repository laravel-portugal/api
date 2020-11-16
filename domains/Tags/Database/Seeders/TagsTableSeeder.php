<?php

namespace Domains\Tags\Database\Seeders;

use Domains\Tags\Database\Factories\TagFactory;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            [ 'name' => 'Eloquent' ],
            [ 'name' => 'Livewire' ],
            [ 'name' => 'Vue' ],
            [ 'name' => 'Testing' ],
        ];
        TagFactory::new()->createMany($tags);
    }
}
