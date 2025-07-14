<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RemoveAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:admin {email} {--super : Only remove super admin privileges, keep admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove admin privileges from a user by email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $superOnly = $this->option('super');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        if ($superOnly) {
            // Only remove super admin privileges
            $user->removeSuperAdmin();
            $this->info("Super admin privileges removed from '{$user->name}' ({$email}). User remains an admin.");
        } else {
            // Remove all admin privileges
            $user->removeAdmin();
            $user->removeSuperAdmin();
            $this->info("All admin privileges removed from '{$user->name}' ({$email}).");
        }

        return 0;
    }
}
