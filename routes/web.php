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

Route::get("/", function () {
    return Inertia::render("Welcome", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
        "laravelVersion" => Application::VERSION,
        "phpVersion" => PHP_VERSION,
    ]);
});

Route::middleware([
    "auth:web,sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    Route::get("/dashboard", function () {
        return Inertia::render("Dashboard");
    })->name("dashboard");

    Route::get("/dashboard/organisations", [
        App\Http\Controllers\OrganisationController::class,
        "index",
    ])->name("organisation.index");

    Route::get("/dashboard/organisations/create", [
        App\Http\Controllers\OrganisationController::class,
        "create",
    ])->name("organisation.create");

    Route::get("/dashboard/organisations/{organisation}", [
        App\Http\Controllers\OrganisationController::class,
        "edit",
    ])->name("organisation.edit");

    Route::post("/dashboard/organisations/create", [
        App\Http\Controllers\OrganisationController::class,
        "store",
    ])->name("organisation.store");

    Route::post("/dashboard/organisations/{organisation}", [
        App\Http\Controllers\OrganisationController::class,
        "update",
    ])->name("organisation.update");

    Route::get("/dashboard/services", [
        App\Http\Controllers\ServiceController::class,
        "index",
    ])->name("service.index");

    Route::get("/dashboard/services/create/{organisation}", [
        App\Http\Controllers\ServiceController::class,
        "create",
    ])->name("service.create");

    Route::get("/dashboard/services/{service}", [
        App\Http\Controllers\ServiceController::class,
        "edit",
    ])->name("service.edit");

    Route::post("/dashboard/services/create", [
        App\Http\Controllers\ServiceController::class,
        "store",
    ])->name("service.store");

    Route::post("/dashboard/services/{service}", [
        App\Http\Controllers\ServiceController::class,
        "update",
    ])->name("service.update");
});
