<?php

namespace App\Console\Commands\Applications;

use App\Models\Application;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateNewApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applications:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new application entry and generates token for it';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("What is the name of the new app?");
        $name = $this->askForName();

        $this->info("What is the domain of the new app?");
        $domain = $this->askForDomain();

        $application = new Application();
        $application->name = $name;
        $application->domain = $domain;
        $application->slug = Str::slug($name);
        $application->save();

        $token = $application->createToken("$name token");

        $this->warn("Your app's token has been created. You will see that token only once, so please save it in safe place.");
        $this->info("Token: " . $token->plainTextToken);

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
            $this->error("The domain cannot be empty.");
            return $this->askForDomain();
        }

        $domain = parse_url($domain,  PHP_URL_HOST);

        if (!$domain) {
            $this->error("Invalid domain format.");
            return $this->askForDomain();
        }

        if (Application::whereDomain($domain)->exists()) {
            $this->error("Application with same domain already exists. Please provide unique domain for the app.");
            return $this->askForName();
        }

        return $domain;
    }

    /**
     * Ask for and validate name
     * @return string
     */
    private function askForName(): string
    {
        $name = trim($this->ask("Name: "));

        if (empty($name)) {
            $this->error("The name cannot be empty");
            return $this->askForName();
        }

        $slug = Str::slug($name);

        if (Application::whereSlug($slug)->exists()) {
            $this->error("Application with similar name already exists. Please provide unique name for the app.");
            return $this->askForName();
        }

        return $name;
    }
}
