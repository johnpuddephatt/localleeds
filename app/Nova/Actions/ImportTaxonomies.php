<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImportTaxonomies extends Action
{
    use InteractsWithQueue, Queueable;

    public $standalone = true;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        \Maatwebsite\Excel\Facades\Excel::import(
            new \App\Imports\TaxonomiesImport(
                $fields->vocabulary,
                $fields->type
            ),
            $fields->file
        );
        return Action::message("Import complete");
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            \Laravel\Nova\Fields\Select::make("Vocabulary")->options([
                "custom" => "Custom",
                "open_eligibility" => "Open Eligibility",
                "lga" => "Local Government Association",
            ]),

            \Laravel\Nova\Fields\Select::make("Type")->options([
                "service_category" => "Service Category",
                "eligibility" => "Eligibility",
                "organisation_type" => "Organisation type",
            ]),
            \Laravel\Nova\Fields\File::make("File")->rules("required"),
        ];
    }
}
