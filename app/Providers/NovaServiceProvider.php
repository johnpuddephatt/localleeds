<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Image;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \Outl1ne\NovaSettings\NovaSettings::addSettingsFields(
            [
                Panel::make("Position one", [
                    Select::make("Category", "featured_category_0")->options(
                        \App\Models\Taxonomy::where("type", "service_category")
                            ->get()
                            ->pluck("name", "id")
                    ),
                    Image::make("Image", "featured_category_0_image"),
                ]),
                Panel::make("Position two", [
                    Select::make("Category", "featured_category_1")->options(
                        \App\Models\Taxonomy::where("type", "service_category")
                            ->get()
                            ->pluck("name", "id")
                    ),
                    Image::make("Image", "featured_category_1_image"),
                ]),
                Panel::make("Position three", [
                    Select::make("Category", "featured_category_2")->options(
                        \App\Models\Taxonomy::where("type", "service_category")
                            ->get()
                            ->pluck("name", "id")
                    ),
                    Image::make("Image", "featured_category_2_image"),
                ]),
            ],
            [],
            "Featured categories"
        );
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define("viewNova", function ($user) {
            return in_array($user->email, [
                "john@letsdance.agency",
                "Jenny.bull@forumcentral.org.uk",
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [new \App\Nova\Dashboards\Main()];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [new \Outl1ne\NovaSettings\NovaSettings()];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
