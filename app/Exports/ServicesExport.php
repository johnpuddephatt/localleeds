<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ServicesExport implements FromCollection, WithHeadings
{
    public function __construct($input)
    {
        $this->input = $input;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Service::applyFilters($this->input)
            ->with("organisation:id,name")
            ->get()
            ->map(function ($item) {
                return [
                    "name" => $item->name,
                    "organisation" => $item->organisation->name,
                    "deliverable_type" => $item->deliverable_type,
                    "url" => $item->url
                        ? "=HYPERLINK(\"" .
                            $item->url .
                            "\",\"" .
                            "Website" .
                            "\")"
                        : null,
                    "email" => $item->email
                        ? "=HYPERLINK(\"mailto:" .
                            $item->email .
                            "\",\"" .
                            $item->email .
                            "\")"
                        : null,
                    "fees" => $item->fees,
                    "attending_type" => $item->attending_type,
                    "attending_access" => $item->attending_access,
                    "wait_time" => $item->wait_time,
                    "referral_process" => $item->referral_process,
                    "view" =>
                        "=HYPERLINK(\"" .
                        route("service.show", ["service" => $item->id]) .
                        "\",\"" .
                        "View on LocalLeeds" .
                        "\")",
                ];
            });
    }

    public function headings(): array
    {
        return [
            "Service name",
            "Organisation",
            "Deliverable type",
            "URL",
            "Email address",
            "Fees",
            "Attending type",
            "Attending access",
            "Wait time",
            "Referral process",
            "View on LocalLeeds",
        ];
    }
}
