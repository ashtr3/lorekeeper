<?php

namespace App\Console\Commands;

use App\Models\Rank\Rank;
use App\Models\User\User;
use App\Models\User\UserAlias;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class QuickStart extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quick-start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the full post-clone setup sequence non-interactively.';

    /**
     * Execute the console command.
     */
    public function handle() {
        $this->info('');
        $this->info('=================================');
        $this->info(' Lorekeeper Quick Start');
        $this->info('=================================');
        $this->info('');

        // Step 1: Validate environment
        $this->info('Validating environment variables...');
        $credentials = $this->validateEnvironment();
        if ($credentials === null) {
            return 1;
        }

        // Step 2: Generate key
        $this->info('Checking application key...');
        if (!$this->generateKeyIfNeeded()) {
            $this->error('Failed to generate application key.');

            return 1;
        }

        // Step 3: Migrations
        $this->info('Running database migrations...');
        if (!$this->runMigrations()) {
            $this->error('Failed to run database migrations.');

            return 1;
        }

        // Step 4: Site settings
        $this->info('Adding site settings...');
        if ($this->call('add-site-settings') !== 0) {
            $this->error('Failed to add site settings.');

            return 1;
        }

        // Step 5: Text pages
        $this->info('Adding text pages...');
        if ($this->call('add-text-pages') !== 0) {
            $this->error('Failed to add text pages.');

            return 1;
        }

        // Step 6: Default images
        $this->info('Copying default images...');
        if ($this->call('copy-default-images') !== 0) {
            $this->error('Failed to copy default images.');

            return 1;
        }

        // Step 7: Admin user
        $this->info('Creating admin user...');
        if (!$this->createAdminUser($credentials)) {
            $this->error('Failed to create admin user.');

            return 1;
        }

        $this->info('');
        $this->info('Setup complete! You can now log in with your admin credentials.');

        return 0;
    }

    /**
     * Validate that required environment variables are present and non-empty.
     *
     * @return array|null Returns credentials array if valid, null if validation fails
     */
    protected function validateEnvironment(): ?array {
        $required = [
            'ADMIN_USER_NAME',
            'ADMIN_USER_EMAIL',
            'ADMIN_USER_PASSWORD',
        ];

        $missing = [];
        foreach ($required as $var) {
            $value = env($var);
            if ($value === null || $value === '') {
                $missing[] = $var;
            }
        }

        if (!empty($missing)) {
            $this->error('Missing required environment variables: '.implode(', ', $missing));

            return null;
        }

        return [
            'name'     => env('ADMIN_USER_NAME'),
            'email'    => env('ADMIN_USER_EMAIL'),
            'password' => env('ADMIN_USER_PASSWORD'),
            'alias'    => env('ADMIN_USER_ALIAS'),
        ];
    }

    /**
     * Generate an application key if one is not already set.
     */
    protected function generateKeyIfNeeded(): bool {
        if (config('app.key') !== null && config('app.key') !== '') {
            $this->info('Application key already set, skipping...');

            return true;
        }

        $exitCode = $this->call('key:generate');

        if ($exitCode !== 0) {
            return false;
        }

        return true;
    }

    /**
     * Run database migrations.
     */
    protected function runMigrations(): bool {
        $exitCode = $this->call('migrate', ['--force' => true]);

        if ($exitCode !== 0) {
            return false;
        }

        return true;
    }

    /**
     * Create the admin user with ranks and optional alias.
     *
     * @param array $credentials Array with keys: name, email, password, alias (nullable)
     */
    protected function createAdminUser(array $credentials): bool {
        // Check if ranks exist; if not, create default ranks
        if (!Rank::count()) {
            Rank::create([
                'name'        => 'Admin',
                'description' => 'Site administrator.',
                'sort'        => 1,
            ]);
            Rank::create([
                'name'        => 'Member',
                'description' => 'A regular member.',
                'sort'        => 0,
            ]);
        }

        // Get the highest-sort rank as the admin rank
        $adminRank = Rank::orderBy('sort', 'DESC')->first();

        // Check if a user with the admin rank already exists
        if (User::where('rank_id', $adminRank->id)->exists()) {
            $this->info('Admin user already exists, skipping...');

            return true;
        }

        // Only set alias and verify email in non-production environments
        $isLocal = !app()->environment('production');

        // Create the admin user via UserService
        $service = new UserService;
        $user = $service->createUser([
            'name'      => $credentials['name'],
            'email'     => $credentials['email'],
            'rank_id'   => $adminRank->id,
            'password'  => $credentials['password'],
            'dob'       => Carbon::createFromFormat('Y-m-d', '1970-01-01'),
            'has_alias' => ($isLocal && !empty($credentials['alias'])) ? 1 : 0,
        ]);

        // Mark email as verified only in non-production environments
        if ($isLocal) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        // If alias is provided and environment is not production, create a UserAlias record
        if ($isLocal && !empty($credentials['alias'])) {
            UserAlias::create([
                'user_id'          => $user->id,
                'site'             => 'deviantart',
                'alias'            => $credentials['alias'],
                'is_primary_alias' => 1,
                'is_visible'       => 1,
            ]);
        }

        return true;
    }
}
