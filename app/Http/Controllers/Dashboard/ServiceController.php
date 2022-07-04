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
        if ($request->user()->isAdministrator()) {
            $services = Service::with("organisation:id,name")->get();
            $organisations = Organisation::select(
                "organisations.id",
                "name"
            )->get();
        } else {
            $services = $request->user()->organisations->count()
                ? Service::where(
                    "organisation_id",
                    $request->user()->organisations->pluck("id")
                )
                    ->with("organisation:id,name")
                    ->get()
                : [];

            $organisations = $request
                ->user()
                ->organisations()
                ->select("organisations.id", "name")
                ->get();
        }

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
        if ($request->user()->cannot("createService", $organisation)) {
            abort(403);
        }

        return Inertia::render("Dashboard/Service/Form", [
            "service_categories" => Taxonomy::where("type", "service_category")
                ->select("id", "label")
                ->orderBy("label")
                ->get(),
            "attending_types" => Taxonomy::where("type", "attending_type")
                ->select("id", "label")
                ->orderBy("label")
                ->get(),
            "attending_accesses" => Taxonomy::where("type", "attending_access")
                ->select("id", "label")
                ->orderBy("label")
                ->get(),
            "deliverable_types" => config("taxonomies.deliverable_types"),
            "organisation" => $organisation,
            "languages" => config("taxonomies.languages"),
            "accessibility_for_disabilities" => config(
                "taxonomies.accessibilities"
            ),
            "eligibilities" => Taxonomy::where("type", "eligibility")
                ->select("id", "name", "label")
                ->get(),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function edit(Request $request, Service $service)
    {
        if ($request->user()->cannot("edit", $service)) {
            abort(403);
        }

        $service->languages = $service->languages()->pluck("language");

        $service->categories = $service->categories()->pluck("id");
        $service->attending_types = $service->attending_types()->pluck("id");
        $service->attending_accesses = $service
            ->attending_accesses()
            ->pluck("id");

        $service->load(
            "fundings",
            "reviews",
            "serviceAreas",
            "contacts.phone",
            "eligibilities.tags",
            "costOptions",
            "locations.physicalAddress",
            "locations.regularSchedules",
            "locations.accessibilityForDisabilities"
        );

        foreach ($service->eligibilities as $eligibility) {
            $eligibility->tags = $eligibility->tags()->pluck("id");
            $eligibility->unsetRelation("tags");
        }

        foreach ($service->locations as $location) {
            $location->accessibility_for_disabilities = $location
                ->accessibilityForDisabilities()
                ->pluck("accessibility");
            $location->unsetRelation("accessibilityForDisabilities");
        }

        return Inertia::render("Dashboard/Service/Form", [
            "service" => $service,
            "service_categories" => Taxonomy::where("type", "service_category")
                ->select("id", "label")
                ->orderBy("label")
                ->get(),
            "attending_types" => Taxonomy::where("type", "attending_type")
                ->select("id", "label")
                ->orderBy("label")
                ->get(),
            "attending_accesses" => Taxonomy::where("type", "attending_access")
                ->select("id", "label")
                ->orderBy("label")
                ->get(),
            "deliverable_types" => config("taxonomies.deliverable_types"),
            "accessibility_for_disabilities" => config(
                "taxonomies.accessibilities"
            ),
            "languages" => config("taxonomies.languages"),
            "eligibilities" => Taxonomy::where("type", "eligibility")
                ->select("id", "name", "label")
                ->get(),
        ]);
    }

    /**
     * @param \App\Http\Requests\ServiceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request, Organisation $organisation)
    {
        if ($request->user()->cannot("storeService", $organisation)) {
            abort(403);
        }

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
        $service->attending_accesses()->sync($request->attending_access);
        $service->attending_types()->sync($request->attending_type);

        session()->flash("flash.banner", "Service succesfully created.");
        session()->flash("flash.bannerStyle", "success");

        return redirect()->route("dashboard.service.index");
    }

    /**
     * @param \App\Http\Requests\ServiceRequest $request
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        if ($request->user()->cannot("update", $service)) {
            abort(403);
        }

        $service->update($request->validated());

        $service->updateLanguages($request->languages);
        $service->updateFundings($request->fundings);
        $service->updateReviews($request->reviews);
        $service->updateServiceAreas($request->service_areas);
        $service->updateContacts($request->contacts);
        $service->updateCostOptions($request->cost_options);
        $service->updateEligibilities($request->eligibilities);
        $service->updateLocations($request->locations);

        $service
            ->taxonomies()
            ->sync(
                array_merge(
                    $request->service_categories,
                    $request->attending_access,
                    $request->attending_type
                )
            );

        return redirect()->route("dashboard.service.index");
    }

    public function delete(Request $request, Service $service)
    {
        if ($request->user()->cannot("delete", $service)) {
            abort(403);
        }
        $service->delete();

        session()->flash("flash.banner", "Service succesfully deleted.");
        session()->flash("flash.bannerStyle", "success");
        return redirect()->route("dashboard.service.index");
    }
}
