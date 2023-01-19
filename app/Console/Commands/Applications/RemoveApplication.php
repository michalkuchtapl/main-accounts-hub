<?php

namespace App\Console\Commands\Applications;

use App\Models\Application;
use Illuminate\Console\Command;

class RemoveApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applications:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("What is the domain of the app?");
        $domain = $this->askForDomain();

        $application = Application::whereDomain($domain)
            ->firstOrFail();

        $confirmation = $this->confirm("Are you sure you want to remove application? ($domain)");

        if (!$confirmation) {
            $this->info("Cancelled");
            return Command::SUCCESS;
        }

        $application->tokens()->delete();
        $application->delete();

        $this->info("Your app has been removed.");

        return Command::SUCCESS;
    }

    /**
     * Ask for and validate domain
     * @return string
     */
    private function askForDomain(): string
    {
        $domain = trim($this->ask("Domain: "));

        if (empty($domain)) {
            $this->error("The domain cannot be empty");
            return $this->askForDomain();
        }

        if (Application::whereDomain($domain)->doesntExist()) {
            $this->error("Application with that domain doesn't exists.");
            return $this->askForName();
        }

        return $domain;
    }
}
