<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test logging system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('TEST LOG PROD');
        Log::error('TEST ERROR PROD');
        $this->info('Logs envoyés !');
    }
}
