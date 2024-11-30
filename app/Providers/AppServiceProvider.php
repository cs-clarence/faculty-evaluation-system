<?php

namespace App\Providers;

use App\Helpers\SectionHelper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        SectionHelper::class => SectionHelper::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $forceRootUrl = Config::get('app.force_root_url');
        if (isset($forceRootUrl) && $forceRootUrl === true || $forceRootUrl === 'true') {
            $appUrl = Config::get('app.url');
            if (isset($appUrl)) {
                URL::forceRootUrl($appUrl);
            }
        }
    }
}
