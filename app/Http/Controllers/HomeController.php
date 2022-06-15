<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    //
    public function __invoke()
    {
        $featured_categories = nova_get_settings([
            "featured_category_0",
            "featured_category_1",
            "featured_category_2",
        ]);

        return Inertia::render("Welcome", [
            "homeServices" => \App\Models\Service::latest()
                ->take(3)
                ->with("organisation:id,name")
                ->withCount("costOptions")
                ->get(),
            "homeCategories" => \App\Models\Taxonomy::where(
                "type",
                "service_category"
            )
                ->where("featured", true)
                ->whereNotIn(
                    "id",
                    nova_get_settings([
                        "featured_category_0",
                        "featured_category_1",
                        "featured_category_2",
                    ])
                )
                ->select("id", "label")
                ->get(),
            "homeFeaturedCategories" => \App\Models\Taxonomy::whereIn(
                "id",
                $featured_categories
            )
                ->orderByRaw(
                    "FIELD(id,'" . implode("','", $featured_categories) . "')"
                )
                ->get()
                ->map(function ($item, $key) {
                    return [
                        "id" => $item->id,
                        "label" => $item->label,
                        "image" => Storage::url(
                            nova_get_setting("featured_category_{$key}_image")
                        ),
                    ];
                }),
        ]);
    }
}
