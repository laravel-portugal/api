<?php

namespace Domains\Links\Database\Seeders;

use Domains\Links\Database\Factories\LinkFactory;
use Domains\Links\Models\Link;
use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    public function run()
    {
        $links = [
            [
                'link' => 'https://www.laravel.pt?name=laravel_livewire_explained',
                'description' => 'Uma página sobre Livewire.',
                'author_name' => 'Artisan One',
                'author_email' => 'artisan_one@laravel.pt',
            ],
            [
                'link' => 'https://www.laravel.pt?name=vuejs_on_the_spotlight',
                'description' => 'Um aritgo sobre VueJS.',
                'author_name' => 'Artisan Two',
                'author_email' => 'artisan_two@laravel.pt',
            ],
        ];
        $tags = [
            [
                [ 'name' => 'Laravel' ],
                [ 'name' => 'Livewire' ],
            ],
            [
                [ 'name' => 'Vue' ],
                [ 'name' => 'Javascript' ],
            ],
        ];
        LinkFactory::new()->approved()->createMany($links)
            ->each(fn (Link $link, $index) => $link->tags()->createMany($tags[$index]));
    }
}
