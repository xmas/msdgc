<?php

namespace App\Console\Commands;

use App\Models\PasswordlessLoginToken;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupPasswordlessTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passwordless:cleanup
                            {--hours=24 : Number of hours to consider tokens expired}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired passwordless login tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoff = Carbon::now()->subHours($hours);

        $deletedCount = PasswordlessLoginToken::where('expires_at', '<', $cutoff)
            ->orWhere('used_at', '<', $cutoff)
            ->delete();

        $this->info("Cleaned up {$deletedCount} expired passwordless login tokens.");

        return Command::SUCCESS;
    }
}
