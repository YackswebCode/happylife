<?php

namespace App\Console\Commands;

use App\Services\MatchingBonusService;
use Illuminate\Console\Command;

class ProcessMatchingBonuses extends Command
{
    protected $signature = 'bonuses:matching';
    protected $description = 'Calculate and award matching bonuses for all users';

    protected $service;

    public function __construct(MatchingBonusService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $this->info('Processing matching bonuses...');
        $count = $this->service->processAll();
        $this->info("Done. Processed {$count} users.");
    }
}