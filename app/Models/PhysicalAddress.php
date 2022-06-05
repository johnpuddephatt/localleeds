<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class PhysicalAddress extends Model
{
    use HasFactory;
    use UuidTrait;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "location_id",
        "address_1",
        "city",
        "state_province",
        "postal_code",
        "country",
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
