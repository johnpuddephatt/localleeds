<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Service;
use App\Models\Taxonomy;

class ServiceController extends \App\Http\Controllers\Controller
{
    public function index(
        Request $request,
        Taxonomy $category,
        $byLocation = null
    ) {
        $services = $category
            ->services()
            ->with("organisation:id,name")

            ->withCount("costOptions")
            ->applyFilters($request->input());

        if ($byLocation) {
            $services = $services->joinLocations();

            $services = $services->with("locations");

            $services = $services->addSelect(["latitude", "longitude"]);
            $services = $services->get()->map(function ($item) {
                return [
                    "id" => $item->id,
                    "name" => $item->name,
                    "organisation" => $item->organisation->name,
                    "latitude" => $item->latitude,
                    "longitude" => $item->longitude,
                    "url" => $item->url,
                ];
            });
        }

        return response()->json($services);
    }
}
