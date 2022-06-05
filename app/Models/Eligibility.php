<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class Eligibility extends Model
{
    use HasFactory;
    use UuidTrait;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["service_id", "minimum_age", "maximum_age"];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "minimum_age" => "integer",
        "maximum_age" => "integer",
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
