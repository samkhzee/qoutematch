<?php

namespace App\Console\Commands;

use App\Lib\SubscriptionExpiryService;
use Illuminate\Console\Command;

class ProcessSubscriptionExpiry extends Command
{
    protected $signature = 'subscriptions:process-expiry';

    protected $description = 'Expire provider subscriptions and send renewal reminders';

    public function handle(): int
    {
        $result = SubscriptionExpiryService::process();

        $this->info("Expired subscriptions: {$result['expired']}");
        $this->info("Expiring-soon reminders: {$result['expiring_soon']}");

        return self::SUCCESS;
    }
}
