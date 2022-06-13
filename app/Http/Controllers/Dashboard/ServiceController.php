<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Models\Organisation;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ServiceController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function index(Request $request)
    {
        $services = \Auth::user()->organisations->count()
            ? Service::where(
                "organisation_id",
                \Auth::user()->organisations->pluck("id")
            )->get()
            : [];
        $organisations = \Auth::user()
            ->organisations()
            ->select("organisations.id", "name")
            ->get();

        return Inertia::render(
            "Dashboard/Service/Index",
            compact("services", "organisations")
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function create(Request $request, Organisation $organisation)
    {
        return Inertia::render("Dashboard/Service/Form", [
            "service_categories" => Taxonomy::where("type", "service_category")
                ->select("id", "name")
                ->get(),
            "organisation_id" => $organisation->id,
            "languages" => config("taxonomies.languages"),
            "accessibility_for_disabilities" => config(
                "taxonomies.accessibilities"
            ),
            "eligibilities" => Taxonomy::where("type", "eligibility")
                ->select("id", "name")
                ->get(),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function edit(Request $request, Service $service)
    {
        $service->languages = $service->languages()->pluck("language");

        $service->categories = $service->categories()->pluck("id");

        $service->load(
            "fundings",
            "reviews",
            "serviceAreas",
            "contacts.phone",
            "eligibilities.tags",
            "costOptions",
            "locations.physicalAddress"
        );

        foreach ($service->eligibilities as $eligibility) {
            $eligibility->tags = $eligibility->tags()->pluck("id");
            $eligibility->unsetRelation("tags");
        }

        return Inertia::render("Dashboard/Service/Form", [
            "service" => $service,
            "service_categories" => Taxonomy::where("type", "service_category")
                ->select("id", "name")
                ->get(),
            "accessibility_for_disabilities" => config(
                "taxonomies.accessibilities"
            ),
            "languages" => config("taxonomies.languages"),
            "eligibilities" => Taxonomy::where("type", "eligibility")
                ->select("id", "name")
                ->get(),
        ]);
    }

    /**
     * @param \App\Http\Requests\ServiceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        $service = Service::create($request->validated());

        $service->updateLanguages($request->languages);
        $service->updateFundings($request->fundings);
        $service->updateReviews($request->reviews);
        $service->updateServiceAreas($request->service_areas);
        $service->updateContacts($request->contacts);
        $service->updateCostOptions($request->cost_options);
        $service->updateEligibilities($request->eligibilities);
        $service->updateLocations($request->locations);

        $service->categories()->sync($request->service_categories);

        return redirect()->route("dashboard.service.index");
    }

    /**
     * @param \App\Http\Requests\ServiceRequest $request
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        $service->updateLanguages($request->languages);
        $service->updateFundings($request->fundings);
        $service->updateReviews($request->reviews);
        $service->updateServiceAreas($request->service_areas);
        $service->updateContacts($request->contacts);
        $service->updateCostOptions($request->cost_options);
        $service->updateEligibilities($request->eligibilities);
        $service->updateLocations($request->locations);

        $service->categories()->sync($request->service_categories);

        return redirect()->route("dashboard.service.index");
    }
}
