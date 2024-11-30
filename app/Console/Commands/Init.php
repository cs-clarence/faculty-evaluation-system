<?php

namespace App\Console\Commands;

use Database\Initializers\DatabaseInitializer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the application database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            (new DatabaseInitializer())->run();
        });
        $this->info("Application initialized");
    }
}
