<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageLinkCommand extends Command
{
    protected $signature = 'storage:link';

    protected $description = 'Create a symbolic link from "public/storage" to "storage/app/public"';

    public function handle(): void
    {
        $publicPath = $this->laravel->basePath('public/storage');

        if (file_exists($publicPath)) {
            $this->error('The "public/storage" directory already exists.');
            return;
        }

        $this->laravel->make('files')->link(
            storage_path('app/public'),
            $publicPath
        );

        $this->info('The [public/storage] directory has been linked.');
    }
}
