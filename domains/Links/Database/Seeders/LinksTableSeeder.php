<?php

namespace Domains\Links\Database\Seeders;

use Domains\Links\Database\Factories\LinkFactory;
use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    public function run(): void
    {
        LinkFactory::times(20)
            ->approved()
            ->create();
    }
}
