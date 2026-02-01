<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CKEditorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish CKEditor assets from resources/ckeditor to public/vendor/ckeditor
        $this->publishes([
            resource_path('ckeditor') => public_path('vendor/ckeditor'),
        ], 'ckeditor-assets');

        // Register @ckeditorScripts Blade directive
        // This outputs the CKEditor script tag and initialization once per page
        Blade::directive('ckeditorScripts', function () {
            return <<<'BLADE'
<?php echo view('components.ckeditor-scripts')->render(); ?>
BLADE;
        });

        // Register @ckeditor('fieldName') Blade directive
        // This creates a textarea that will be initialized as CKEditor
        Blade::directive('ckeditor', function ($expression) {
            // Parse arguments: name, profile (optional)
            $args = array_map('trim', explode(',', $expression));
            $nameInd = $args[0];
            $profileInd = $args[1] ?? "'default'";
            
            // value for old() needs clean name string
            $nameString = trim($nameInd, " '\"");

            return <<<BLADE
<?php echo view('components.ckeditor-field', [
    'fieldName' => {$nameInd}, 
    'value' => old('{$nameString}', \$value ?? ''), 
    'profile' => {$profileInd}
])->render(); ?>
BLADE;
        });
    }
}

