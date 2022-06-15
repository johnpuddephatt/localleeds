<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", App\Http\Controllers\HomeController::class)->name("home");

Route::get("organisations", [
    App\Http\Controllers\OrganisationController::class,
    "index",
])->name("organisation.index");

Route::get("organisation/{organisation}", [
    App\Http\Controllers\OrganisationController::class,
    "show",
])->name("organisation.show");

Route::get("service/{service}", [
    App\Http\Controllers\ServiceController::class,
    "show",
])->name("service.show");

Route::get("services/{view?}", [
    App\Http\Controllers\ServiceController::class,
    "index",
])->name("service.index");

Route::middleware([
    "auth:web,sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    // Route::get("/dashboard", function () {
    //     return Inertia::render("Dashboard");
    // })->name("dashboard");

    Route::permanentRedirect("dashboard", "dashboard/organisations")->name(
        "dashboard"
    );

    Route::get("/dashboard/organisations", [
        App\Http\Controllers\Dashboard\OrganisationController::class,
        "index",
    ])->name("dashboard.organisation.index");

    Route::get("/dashboard/organisations/create", [
        App\Http\Controllers\Dashboard\OrganisationController::class,
        "create",
    ])->name("dashboard.organisation.create");

    Route::get("/dashboard/organisations/{organisation}", [
        App\Http\Controllers\Dashboard\OrganisationController::class,
        "edit",
    ])->name("dashboard.organisation.edit");

    Route::post("/dashboard/organisations/create", [
        App\Http\Controllers\Dashboard\OrganisationController::class,
        "store",
    ])->name("dashboard.organisation.store");

    Route::post("/dashboard/organisations/{organisation}", [
        App\Http\Controllers\Dashboard\OrganisationController::class,
        "update",
    ])->name("dashboard.organisation.update");

    Route::get("/dashboard/services", [
        App\Http\Controllers\Dashboard\ServiceController::class,
        "index",
    ])->name("dashboard.service.index");

    Route::get("/dashboard/services/create/{organisation}", [
        App\Http\Controllers\Dashboard\ServiceController::class,
        "create",
    ])->name("dashboard.service.create");

    Route::get("/dashboard/services/{service}", [
        App\Http\Controllers\Dashboard\ServiceController::class,
        "edit",
    ])->name("dashboard.service.edit");

    Route::post("/dashboard/services/create/{organisation}", [
        App\Http\Controllers\Dashboard\ServiceController::class,
        "store",
    ])->name("dashboard.service.store");

    Route::post("/dashboard/services/{service}", [
        App\Http\Controllers\Dashboard\ServiceController::class,
        "update",
    ])->name("dashboard.service.update");
});
