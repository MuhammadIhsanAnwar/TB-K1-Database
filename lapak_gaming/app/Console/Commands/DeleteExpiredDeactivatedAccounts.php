<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteExpiredDeactivatedAccounts extends Command
{
    protected $signature = 'accounts:delete-expired-deactivated';

    protected $description = 'Delete permanently accounts that have been deactivated for more than 6 months';

    public function handle()
    {
        $expiredUsers = User::onlyTrashed()
            ->whereNotNull('deactivated_at')
            ->where('deactivated_at', '<', now()->subMonths(6))
            ->get();

        $count = $expiredUsers->count();

        if ($count === 0) {
            $this->info('No expired deactivated accounts found.');
            return;
        }

        $this->info("Found {$count} expired deactivated accounts. Deleting...");

        foreach ($expiredUsers as $user) {
            $user->forceDelete();
            $this->line("Deleted account: {$user->email}");
        }

        $this->info("Successfully deleted {$count} expired deactivated accounts.");
    }
}