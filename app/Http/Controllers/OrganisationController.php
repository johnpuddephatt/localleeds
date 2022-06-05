<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganisationRequest;
use App\Models\Organisation;
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
        $organisations = Organisation::all();

        return Inertia::render("Organisation/Index", compact("organisations"));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function edit(Request $request, Organisation $organisation)
    {
        return Inertia::render("Organisation/Form", compact("organisation"));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Inertia
     */
    public function create(Request $request)
    {
        return Inertia::render("Organisation/Form");
    }

    /**
     * @param \App\Http\Requests\OrganisationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganisationRequest $request)
    {
        $organisation = Organisation::create($request->validated());

        session()->flash("flash.banner", "Organisation succesfully created.");
        session()->flash("flash.bannerStyle", "success");

        return redirect()->route("organisation.index");
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

        session()->flash("flash.banner", "Organisation updated.");
        session()->flash("flash.bannerStyle", "success");

        return redirect()->route("organisation.index");
    }
}
