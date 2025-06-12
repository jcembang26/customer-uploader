<?php

namespace App\Console\Commands;

use App\Services\DataImporterService;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:import {--limit=100} {--nat=au} {--chunk=50} {--error=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for importing customer to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chunk = (int) $this->option('chunk') ?? 50;
        $error = (int) $this->option('error') ?? 0;
        $limit = (int) $this->option('limit') ?? 100;
        $nat = $this->option('nat') ?? 'au';

        $params = [
            'chunk' => $chunk,
            'error' => $error,
            'limit' => $limit,
            'nat' => $nat,
        ];

        $service = app(DataImporterService::class);
        $this->info(print_r($service->import($params), true));
    }
}