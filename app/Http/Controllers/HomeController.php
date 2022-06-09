<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    //
    public function __invoke()
    {
        return Inertia::render("Welcome", [
            "homeCategories" => \App\Models\Taxonomy::where(
                "type",
                "service_category"
            )
                ->whereNotIn(
                    "id",
                    nova_get_settings([
                        "featured_category_0",
                        "featured_category_1",
                        "featured_category_2",
                    ])
                )
                ->get(),
            "homeFeaturedCategories" => \App\Models\Taxonomy::whereIn(
                "id",
                nova_get_settings([
                    "featured_category_0",
                    "featured_category_1",
                    "featured_category_2",
                ])
            )
                ->get()
                ->map(function ($item, $key) {
                    return [
                        "id" => $item->id,
                        "name" => $item->name,
                        "image" => nova_get_setting(
                            "featured_category_{$key}_image"
                        ),
                    ];
                }),
        ]);
    }
}
