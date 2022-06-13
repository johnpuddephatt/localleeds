<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::enforceMorphMap([
            "organization" => "App\Models\Organisation",
            "eligibility" => "App\Models\Eligibility",
            "cost_option" => "App\Models\CostOption",
            "service_area" => "App\Models\ServiceArea",
            "service" => "App\Models\Service",
            "taxonomy" => "App\Models\Taxonomy",
        ]);
    }
}
