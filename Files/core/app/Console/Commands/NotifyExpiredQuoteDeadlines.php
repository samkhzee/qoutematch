<?php

namespace App\Console\Commands;

use App\Lib\QuoteDeadlineService;
use Illuminate\Console\Command;

class NotifyExpiredQuoteDeadlines extends Command
{
    protected $signature = 'quotes:notify-expired-deadlines';

    protected $description = 'Notify admin and matching providers when a request quote deadline has expired (once per request)';

    public function handle(): int
    {
        $count = QuoteDeadlineService::processExpiryNotifications();

        $this->info("Processed {$count} expired quote deadline notification(s).");

        return self::SUCCESS;
    }
}
