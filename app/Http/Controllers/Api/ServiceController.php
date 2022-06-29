<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Service;

class ServiceController extends \App\Http\Controllers\Controller
{
    public function index(Request $request, $category, $byLocation = null)
    {
        $services = Service::query();
        $services = $services->with("organisation:id,name");

        if ($request->category) {
            $services = $services->whereHas("categories", function (
                Builder $query
            ) use ($request) {
                $query->where("id", $request->category);
            });
        }

        if ($byLocation) {
            $services = $services->joinLocations();
            // $services = $services->with("locations");
            // $services = $services->get()->map(function ($item) {
            //     return [
            //         "id" => $item->id,
            //         "name" => $item->name,
            //         "organisation" => $item->organisation->name,
            //         "cost_options_count" => $item->cost_options_count,
            //         "latitude" => $item->latitude,
            //         "longitude" => $item->longitude,
            //     ];
            // });
            $services = $services->get();
        } else {
            $services = $services->with("locations");
            $services = $services->get();
        }

        return response()->json($services);
    }
}
