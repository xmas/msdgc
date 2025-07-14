<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email} {--super : Also grant super admin privileges}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote a user to admin by email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $super = $this->option('super');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        // Make user admin
        $user->makeAdmin();
        $this->info("User '{$user->name}' ({$email}) has been promoted to admin.");

        // Make super admin if requested
        if ($super) {
            $user->makeSuperAdmin();
            $this->info("User '{$user->name}' has been granted super admin privileges and can access /cp");
        }

        return 0;
    }
}
