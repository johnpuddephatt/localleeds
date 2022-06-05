<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UuidTrait;
use Carbon\Carbon;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    use UuidTrait;

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->assured_date = Carbon::now();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "organisation_id",
        "name",
        "description",
        "url",
        "email",
        "status",
        "fees",
        "deliverable_type",
        "assured_date",
        "attending_type",
        "attending_access",
        "wait_time",
        "referral_process",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "assured_date" => "date",
    ];

    public function taxonomies()
    {
        return $this->belongsToMany(Taxonomy::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function fundings()
    {
        return $this->hasMany(Funding::class);
    }

    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function costOptions()
    {
        return $this->hasMany(CostOption::class);
    }

    public function regularSchedules()
    {
        return $this->hasMany(RegularSchedule::class);
    }

    public function serviceAreas()
    {
        return $this->hasMany(ServiceArea::class);
    }

    public function eligibilities()
    {
        return $this->hasMany(Eligibility::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function phones()
    {
        return $this->hasManyThrough(Phone::class, Contact::class);
    }

    public function updateLanguages($languages)
    {
        if (
            $languages &&
            $this->languages->pluck("language")->toArray() !== $languages
        ) {
            $this->languages()->delete();
            $this->languages()->createMany(
                array_map(function ($item) {
                    return ["language" => $item];
                }, $languages)
            );
        }
    }

    public function updateFundings($fundings)
    {
        $this->fundings()->delete();
        $this->fundings()->createMany($fundings);
    }

    public function updateReviews($reviews)
    {
        $this->reviews()->delete();
        $this->reviews()->createMany($reviews);
    }

    public function updateServiceAreas($service_areas)
    {
        $this->serviceAreas()->delete();
        $this->serviceAreas()->createMany($service_areas);
    }

    public function updateCostOptions($cost_options)
    {
        $this->costOptions()->delete();
        $this->costOptions()->createMany($cost_options);
    }

    public function updateContacts($contacts)
    {
        $this->phones()->delete();
        $this->contacts()->delete();

        foreach ($contacts as $contact) {
            $new_contact = $this->contacts()->create($contact);
            $new_contact->phone()->create($contact["phone"]);
        }
    }

    public function updateEligibilities($eligibilities)
    {
        $this->eligibilities()->delete();
        $this->eligibilities()->createMany($eligibilities);
    }

    public function updateLocations($locations)
    {
        $this->locations()->detach();

        foreach ($locations as $location) {
            $new_location = $this->locations()->firstOrCreate(
                [
                    "id" => $location["id"] ?? null,
                ],
                [
                    "name" => $location["name"],
                    "latitude" => $location["latitude"],
                    "longitude" => $location["longitude"],
                ]
            );
            $new_location->physicalAddress()->delete();
            $new_location
                ->physicalAddress()
                ->create($location["physical_address"]);
        }
    }
}
