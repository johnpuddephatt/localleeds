<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Models\Organisation;
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
        $services = Service::all();
        $organisations = Organisation::select("id", "name")->get();

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
            "organisation_id" => $organisation->id,
            "languages" => config("taxonomies.languages"),
            "accessibility_for_disabilities" => config(
                "taxonomies.accessibilities"
            ),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function edit(Request $request, Service $service)
    {
        $accessibility_for_disabilities = config("taxonomies.accessibilities");
        $languages = config("taxonomies.languages");
        $service->languages = $service
            ->languages()
            ->pluck("language")
            ->toArray();
        $service->load(
            "fundings",
            "reviews",
            "serviceAreas",
            "contacts.phone",
            "eligibilities",
            "costOptions",
            "locations.physicalAddress"
        );
        return Inertia::render(
            "Dashboard/Service/Form",
            compact("service", "languages")
        );
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

        return redirect()->route("dashboard.service.index");
    }
}
