<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganisationRequest;
use App\Models\Organisation;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganisationController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $organisations = \Auth::user()->organisations;

        return Inertia::render(
            "Dashboard/Organisation/Index",
            compact("organisations")
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function edit(Request $request, Organisation $organisation)
    {
        $organisation_types = Taxonomy::where("type", "organisation_type")
            ->select("id", "name")
            ->get();

        $organisation->types = $organisation->types()->pluck("id");

        return Inertia::render(
            "Dashboard/Organisation/Form",
            compact("organisation", "organisation_types")
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function create(Request $request)
    {
        $organisation_types = Taxonomy::where("type", "organisation_type")
            ->select("id", "name")
            ->get();

        return Inertia::render(
            "Dashboard/Organisation/Form",
            compact("organisation_types")
        );
    }

    /**
     * @param \App\Http\Requests\OrganisationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganisationRequest $request)
    {
        $organisation = \Auth::user()
            ->organisations()
            ->create($request->validated());

        $organisation->types()->sync($request->types);

        session()->flash("flash.banner", "Organisation succesfully created.");
        session()->flash("flash.bannerStyle", "success");

        return redirect()->route("dashboard.organisation.index");
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function update(
        OrganisationRequest $request,
        Organisation $organisation
    ) {
        $organisation->update($request->validated());

        $organisation->types()->sync($request->types);

        session()->flash("flash.banner", "Organisation updated.");
        session()->flash("flash.bannerStyle", "success");

        return redirect()->route("dashboard.organisation.index");
    }
}
