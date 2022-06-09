<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class Location extends Model
{
    use HasFactory;
    use UuidTrait;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["name", "description", "latitude", "longitude"];

    protected $with = ["physicalAddress"];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "latitude" => "double",
        "longitude" => "double",
    ];

    // public function organisations()
    // {
    //     return $this->belongsToMany(Organsation::class);
    // }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function physicalAddress()
    {
        return $this->hasOne(PhysicalAddress::class);
    }

    public function accessibilityForDisabilities()
    {
        return $this->hasMany(AccessibilityForDisabilities::class);
    }

    public function regularSchedules()
    {
        return $this->hasMany(RegularSchedule::class);
    }
}
