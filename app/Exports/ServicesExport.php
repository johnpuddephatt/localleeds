<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ServicesExport implements FromQuery, WithHeadings
{
    public function __construct($input)
    {
        $this->input = $input;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return Service::applyFilters($this->input);
    }

    public function headings(): array
    {
        return [
            "#",
            "#",
            "Service name",
            "Service description",
            "URL",
            "Email address",
            "Status",
            "",
            "Category",
            "Date last verified",
            "Delivery type",
            "Access type",
            "",
            "created",
            "updated",
            "Referral time",
            "Referral process",
            "Distance",
        ];
    }
}
