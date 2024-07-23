<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class UpdateCommonTerms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-common-terms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update language files with dynamic values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('config:clear');

        $this->processRouteUpdates();
        $this->info('Route files updated successfully.');
        $this->call('route:clear');

        $this->processLangUpdates();
        $this->info('Language files updated successfully.');
    }

    protected function processRouteUpdates() 
    {
        $commonTerms = config('lorekeeper.common_terms');
        $basePath = base_path('routes');

        $this->processDirectory($basePath, function ($filePath) use ($commonTerms) {
            $content = File::get($filePath);

            // Define patterns to match Route::get, Route::post, and Route::group declarations
            $patterns = [
                '/^(Route::(?:get|post))\((.*?)\)/m', // Route::get or Route::post declarations
                '/^(Route::group)\(\[\'prefix\'\s*=>\s*\'(.*?)\'/m', // Route::group with prefix declaration
            ];

            // Callback function to perform replacements
            $content = preg_replace_callback_array([
                $patterns[0] => function ($matches) use ($commonTerms) {
                    return $this->replaceInRouteDeclaration($matches[1], $matches[2], $commonTerms);
                },
                $patterns[1] => function ($matches) use ($commonTerms) {
                    return $this->replaceInGroupDeclaration($matches[1], $matches[2], $commonTerms);
                },
            ], $content);

            // Save the modified content back to the file
            File::put($filePath, $content);
        });
    }

    protected function replaceInRouteDeclaration($routeMethod, $args, $commonTerms)
    {
        // Regex to match the first set of single quotes
        $quotePattern = "/'([^']+)'/";
        preg_match($quotePattern, $args, $quoteMatches);

        if (!empty($quoteMatches[1])) {
            $originalRoute = $quoteMatches[1];

            // Perform replacements on the original route
            foreach ($commonTerms as $term => $value) {
                $slugTerm = Str::slug($term);
                $slugValue = Str::slug($value);

                // Replace the term and its plural in the original route
                $originalRoute = str_replace($slugTerm, $slugValue, $originalRoute);
                $originalRoute = str_replace(Str::plural($slugTerm), Str::plural($slugValue), $originalRoute);
            }

            // Replace the original route inside the arguments
            $args = str_replace($quoteMatches[1], $originalRoute, $args);
        }

        // Return the modified route declaration
        return "$routeMethod($args)";
    }

    protected function replaceInGroupDeclaration($routeMethod, $args, $commonTerms)
    {
        if (!empty($args)) {
            // Perform replacements on the original prefix
            foreach ($commonTerms as $term => $value) {
                $slugTerm = Str::slug($term);
                $slugValue = Str::slug($value);

                // Replace the term and its plural in the original prefix
                $args = str_replace($slugTerm, $slugValue, $args);
                $args = str_replace(Str::plural($slugTerm), Str::plural($slugValue), $args);
            }
        }

        // Return the modified group declaration
        return "$routeMethod(['prefix' => '$args'";
    }

    protected function processLangUpdates()
    {
        $langUpdates = config('lorekeeper.lang_updates');

        foreach ($langUpdates as $file => $keys) {
            $filePath = resource_path("lang/en/$file.php");
            if (File::exists($filePath)) {
                $file = require $filePath;
                foreach ($keys as $key => $value) {
                    if (isset($file[$key])) {
                        $updatedValue = $this->processValue($value);
                        $file[$key] = $updatedValue;
                    }
                }
                File::put($filePath, '<?php return ' . var_export($file, true) . ';');   
            }
        }
    }

    protected function processValue($value)
    {
        $matches = [];
        preg_match_all('/{([^}]*)}/', $value, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $placeholder) {
                $transforms = explode('|', $placeholder);
                $base_value = config("lorekeeper.common_terms.$transforms[0]");

                if (!$base_value) {
                    return $value;
                }

                foreach ($transforms as $transform) {
                    switch ($transform) {
                        case 'ucfirst':
                            $base_value = ucfirst($base_value);
                            break;
                        case 'ucwords':
                            $base_value = ucwords($base_value);
                            break;
                        case 'plural':
                            $base_value = str_plural($base_value);
                            break;
                    }
                }

                return str_replace("{{$placeholder}}", $base_value, $value);
            }
        }

        return $value;
    } 

    protected function processDirectory($directory, $callback)
    {
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $callback($file->getPathname());
            }
        }
    }
}
