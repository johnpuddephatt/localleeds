<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\ServicesExport;
use Maatwebsite\Excel\Facades\Excel;

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
            "attending_types:id,label",
            "attending_accesses:id,label",
        ]);

        return Inertia::render("Service/Show", [
            "service" => $service,
            "canEdit" => $request->user()
                ? $request->user()->can("edit", $service)
                : false,
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(
            new ServicesExport($request->input()),
            "services.xlsx"
        );
    }

    public function index(Request $request, $view = "list")
    {
        $services = \App\Models\Service::with("organisation:id,name")
            ->withCount("costOptions")
            ->applyFilters($request->input());

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
                ->orderBy("label")
                ->get(),
        ]);
    }
}
