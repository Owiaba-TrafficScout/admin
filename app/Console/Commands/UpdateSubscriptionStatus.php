<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class UpdateSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subscription statuses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('updating subscription statuses...');

        $now = now();

        //update subscriptions where the end date has passed the current date
        Subscription::where('end_date', '<', $now)
            ->where('status_id', 1)
            ->update(['status_id' => 2]);

        $this->info('subscriptions updated successfully');
        return 0;
    }
}
