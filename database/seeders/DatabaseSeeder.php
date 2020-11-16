<?php

namespace Database\Seeders;

use Domains\Links\Database\Seeders\LinksTableSeeder;
use Domains\Tags\Database\Seeders\TagsTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('production')) {
            abort(1, 'Not allowed in production!');
        }
        $this->call(LinksTableSeeder::class);
    }
}
