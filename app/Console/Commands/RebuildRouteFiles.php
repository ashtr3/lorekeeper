<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RebuildRouteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:regenerate
                            {--json=storage/app/routes.json : Path to the JSON produced by routes:list}
                            {--dry   : Show what would happen without writing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-generate route files from the saved JSON and back-up the originals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonPath = base_path($this->option('json'));

        if (!File::exists($jsonPath)) {
            $this->error("JSON file not found: {$jsonPath}");
            return self::FAILURE;
        }

        $routes = collect(json_decode(File::get($jsonPath), true));

        if ($routes->isEmpty()) {
            $this->error("No routes found in JSON.");
            return self::FAILURE;
        }
        
        $groups = $routes->groupBy(fn ($r) => $r['source'] ?? '_unknown')
                         ->filter(fn ($_, $key) => $key !== '_unknown');

        $timestamp = Carbon::now()->format('Ymd_His');
        $dryRun    = $this->option('dry');

        foreach ($groups as $file => $fileRoutes) {
            $absPath  = base_path($file);
            $backup   = "{$absPath}.{$timestamp}.bak";

            // 1. Back-up original file if it exists
            if (File::exists($absPath)) {
                if ($dryRun) {
                    $this->line("[dry] mv {$absPath} {$backup}");
                } else {
                    File::move($absPath, $backup);
                    $this->info("Backed up {$file} → {$backup}");
                }
            } else {
                $this->warn("Original file missing, creating new: {$file}");
            }

            // 2. Build new file contents
            $contents = "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n";

            foreach ($fileRoutes as $route) {
                $methods = explode('|', $route['method']);
                $uri     = $route['uri'];
                $action  = $route['action'];
                $name    = $route['name'] ? "->name('{$route['name']}')" : '';

                // middleware string → array
                $middleware = collect(explode(',', $route['middleware'] ?? ''))
                                ->map(fn ($m) => trim($m))   // ← explicit trim
                                ->filter()
                                ->values();


                // build the route line
                if (count($methods) === 1) {
                    $method  = strtolower($methods[0]);
                    $line    = "Route::{$method}('{$uri}', '{$action}')";
                } else {
                    $methodList = collect($methods)->map(fn ($m) => "'{$m}'")->implode(', ');
                    $line       = "Route::match([{$methodList}], '{$uri}', '{$action}')";
                }

                if ($middleware->isNotEmpty()) {
                    $mw = $middleware->map(fn ($m) => "'{$m}'")->implode(', ');
                    $line .= "->middleware([{$mw}])";
                }

                if ($name) {
                    $line .= $name;
                }

                $contents .= "{$line};\n";
            }

            // 3. Write (or preview) the new route file
            if ($dryRun) {
                $this->line("[dry] write {$absPath}");
            } else {
                File::ensureDirectoryExists(dirname($absPath));
                File::put($absPath, $contents);
                $this->info("Re-created {$file}");
            }
        }

        $this->info($dryRun ? 'Dry-run complete.' : 'All route files rebuilt.');
        return self::SUCCESS;
    }
}
