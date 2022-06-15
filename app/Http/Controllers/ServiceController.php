<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;

class ServiceController extends Controller
{
    public function show(Request $request, Service $service)
    {
        $service->load([
            "organisation",
            "contacts",
            "locations",
            "costOptions",
            "reviews",
            "fundings",
            "eligibilities",
        ]);

        return Inertia::render("Service/Show", [
            "service" => $service,
            "canEdit" => $request->user()
                ? $request->user()->can("edit", $service)
                : false,
        ]);
    }

    public function index(Request $request, $view = "list")
    {
        $services = \App\Models\Service::query();
        $services = $services
            ->with("organisation:id,name")
            ->withCount("costOptions");

        if ($request->input("service_category")) {
            $services = $services->whereHas("categories", function (
                Builder $query
            ) use ($request) {
                $query->where("id", $request->service_category);
            });
        }

        if ($request->input("postcode")) {
            $services = $services->postcodeFilter(
                $request->input("postcode"),
                $request->input("distance") ?? 3 // distance
            );
        }

        if ($request->input("free") === "true") {
            $services = $services->freefilter();
        }

        if ($view == "list") {
            $services = $services
                ->paginate(8)
                ->withQueryString()
                ->fragment("results")
                ->through(function ($item) {
                    return [
                        "id" => $item->id,
                        "name" => $item->name,
                        "organisation" => $item->organisation->name,
                        "cost_options_count" => $item->cost_options_count,
                        "distance" => number_format($item->distance, 0),
                    ];
                });
        }

        if ($view == "map" && !$request->input("postcode")) {
            $services = $services->joinLocations();
        }

        if ($view == "map") {
            $services = $services->with("locations");

            $services = $services->addSelect(["latitude", "longitude"]);
            $services = $services->get()->map(function ($item) {
                return [
                    "id" => $item->id,
                    "name" => $item->name,
                    "organisation" => $item->organisation->name,
                    "cost_options_count" => $item->cost_options_count,
                    "latitude" => $item->latitude,
                    "longitude" => $item->longitude,
                    "distance" => number_format($item->distance, 0),
                ];
            });
        }

        return Inertia::render("Service/Index", [
            "services" => $services,
            "view" => $view,
            "filters" => $request->input(),
            "service_categories" => \App\Models\Taxonomy::where(
                "type",
                "service_category"
            )
                ->select("id", "label")
                ->get(),
        ]);
    }
}
