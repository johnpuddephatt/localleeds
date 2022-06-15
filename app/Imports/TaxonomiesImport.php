<?php

namespace App\Imports;

use App\Models\Taxonomy;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class TaxonomiesImport implements ToModel
{
    public function __construct($vocabulary, $type)
    {
        $this->vocabulary = $vocabulary;
        $this->type = $type;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Taxonomy([
            "id" => Str::uuid(36),
            "name" => $row[0],
            "label" => $row[1],
            "featured" => $row[2],
            "type" => $this->type,
            "vocabulary" => $this->vocabulary,
        ]);
    }
}
