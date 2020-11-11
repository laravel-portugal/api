<?php

namespace App\Console\Commands;

use Domains\Links\Database\Factories\LinkFactory;
use Domains\Tags\Database\Factories\TagFactory;
use Illuminate\Console\Command;

class FrontendHelperCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "fh {method=main}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Frontend helper. Usage e.g: `php artisan fh links`";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (app()->environment('production')) {
            $this->info('Not allowed to run in production.');
            return 0;
        }
        $exitCode = $this->{$this->argument('method')}();
        return $exitCode === null ? 0 : $exitCode;
    }

    private function getLogo()
    {
        return <<<EOT
         _                               _  ______          _                     _
        | |                             | | | ___ \        | |                   | |
        | |     __ _ _ __ __ ___   _____| | | |_/ /__  _ __| |_ _   _  __ _  __ _| |
        | |    / _` | '__/ _` \ \ / / _ \ | |  __/ _ \| '__| __| | | |/ _` |/ _` | |
        | |___| (_| | | | (_| |\ V /  __/ | | | | (_) | |  | |_| |_| | (_| | (_| | |
        \_____/\__,_|_|  \__,_| \_/ \___|_| \_|  \___/|_|   \__|\__,_|\__, |\__,_|_|
                                                                       __/ |
                                                                      |___/
        EOT;
    }

    private function main()
    {
        $this->warn($this->getLogo());
        $this->info("I'm your artisan frontend helper. Run `php artisan <method>.`");
        $this->warn('Available:');
        $this->table(['method'],
            [
                ['method' => 'migrateFresh'],
                ['method' => 'links'],
                ['method' => 'tags'],
            ]
        );
    }

    private function migrateFresh()
    {
        $this->call('migrate:fresh');
    }

    private function links()
    {
        $emailAddresses = ['artisan1@laravel.pt', 'artisan2@laravel.pt'];
        foreach ($emailAddresses as $emailAddress) {
            LinkFactory::new()->approved()->withAuthorEmail($emailAddress)->create();
            $this->info("Successfully inserted {$emailAddress}");
        }
    }

    private function tags()
    {
        $tags = ['Eloquent', 'Livewire', 'Vue', 'Testing'];
        foreach ($tags as $tag) {
            TagFactory::new()->create(['name' => $tag]);
            $this->info("Successfully inserted {$tag}");
        }
    }
}
