<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ListRoutes extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all registered routes in the application';

    /**
     * Execute the console command.
     */
    public function handle() {
        $routes = $this->getRoutes();

        $json = $routes->toJson(JSON_PRETTY_PRINT);
        $path = storage_path('app/routes.json');

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $json);

        $this->info("Route list saved to: {$path}");

        return Command::SUCCESS;
    }

    private function getRoutes() {
        return collect(Route::getRoutes())
            ->filter(function ($route) {
                $source = $route->getAction('source_file') ?? '';

                return str_contains($source, 'routes/lorekeeper');
            })
            ->map(function ($route) {
                return [
                    'method'     => implode('|', $route->methods()),
                    'uri'        => $route->uri(),
                    'name'       => $route->getName(),
                    'action'     => $route->getActionName(),
                    'middleware' => implode(', ', $route->middleware()),
                    'source'     => $route->getAction('source_file') ?? null,
                ];
            });
    }
}
