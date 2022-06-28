<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Service;

class ServiceController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
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

        $services = $services->get();

        return response()->json($services);
    }
}
