<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisation;
use Inertia\Inertia;

class OrganisationController extends Controller
{
    public function show(Request $request, Organisation $organisation)
    {
        $services = $organisation
            ->services()
            ->withCount("costOptions")
            ->paginate(6)
            ->fragment("services")
            ->through(function ($item) {
                return [
                    "id" => $item->id,
                    "name" => $item->name,
                    "organisation" => $item->organisation->name,
                    "cost_options_count" => $item->cost_options_count,
                    "distance" => number_format($item->distance, 0),
                ];
            });

        return Inertia::render("Organisation/Show", [
            "organisation" => $organisation->load("types:name"),
            "services" => $services,
            "canEdit" => $request->user()
                ? $request->user()->can("edit", $organisation)
                : false,
        ]);
    }

    public function index()
    {
        return Organisation::select("id", "name")->get();
    }
}
