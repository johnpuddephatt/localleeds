<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UuidTrait;
use Carbon\Carbon;
use App\Traits\GeographicalTrait;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    use UuidTrait;
    use GeographicalTrait;

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
        "assured_date" => "date:jS F Y",
    ];

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function addresses()
    {
        return $this->hasManyThrough(PhysicalAddress::class, Location::class);
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

    public function taxonomies()
    {
        return $this->morphToMany(Taxonomy::class, "link", "link_taxonomy");
    }

    public function categories()
    {
        return $this->morphToMany(
            Taxonomy::class,
            "link",
            "link_taxonomy"
        )->where("type", "=", "service_category");
    }

    public function attending_accesses()
    {
        return $this->morphToMany(
            Taxonomy::class,
            "link",
            "link_taxonomy"
        )->where("type", "=", "attending_access");
    }

    public function attending_types()
    {
        return $this->morphToMany(
            Taxonomy::class,
            "link",
            "link_taxonomy"
        )->where("type", "=", "attending_type");
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

        foreach ($eligibilities as $eligibility) {
            $new_eligibility = $this->eligibilities()->create($eligibility);
            if (isset($eligibility["tags"])) {
                $new_eligibility->tags()->sync($eligibility["tags"]);
            }
        }
    }

    public function updateLocations($locations)
    {
        $this->locations()->detach();

        foreach ($locations as $location) {
            $new_location = $this->locations()->updateOrCreate(
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

            $new_location->regularSchedules()->delete();
            $new_location
                ->regularSchedules()
                ->createMany($location["regular_schedules"]);

            $new_location->accessibilityForDisabilities()->delete();

            if (isset($location["accessibility_for_disabilities"])) {
                $new_location->accessibilityForDisabilities()->createMany(
                    array_map(function ($accessibility) {
                        return ["accessibility" => $accessibility];
                    }, $location["accessibility_for_disabilities"])
                );
            }
        }
    }

    public function scopeApplyFilters($query, $input)
    {
        return $query
            ->categoryFilter($input["service_category"] ?? null)
            ->postcodeFilter(
                $input["postcode"] ?? null,
                $input["distance"] ?? 3 // distance
            )
            ->freefilter($input["free"] ?? null);
    }

    public function scopeJoinLocations($query)
    {
        return $query
            ->join("location_service", "id", "=", "location_service.service_id")
            ->join(
                "locations",
                "locations.id",
                "=",
                "location_service.location_id"
            );
    }

    public function scopeServiceDistance($query, $latitude, $longitude)
    {
        if ($latitude && $longitude) {
            return $query
                ->joinLocations()
                ->distance($latitude, $longitude, [
                    "table" => "locations",
                ])
                ->orderBy("distance");
        }
    }

    public function scopeFreeFilter($query, $free)
    {
        if ($free) {
            return $query->doesntHave("costOptions");
        }
    }

    public function scopeCategoryFilter($query, $category)
    {
        if ($category) {
            return $query->whereHas("categories", function (
                Builder $query
            ) use ($category) {
                $query->where("id", $category);
            });
        }
    }

    public function scopePostcodeFilter($query, $postcode, $distance)
    {
        if ($postcode) {
            $client = new Client(["http_errors" => false]);
            $postcodesIoResponse = $client->request(
                "GET",
                "https://api.postcodes.io/postcodes/" . $postcode
            );

            if ($postcodesIoResponse->getStatusCode() == 200) {
                $postcodesIoResult = json_decode(
                    $postcodesIoResponse->getBody(),
                    true
                )["result"];

                return $query
                    ->join(
                        "location_service",
                        "id",
                        "=",
                        "location_service.service_id"
                    )
                    ->join(
                        "locations",
                        "locations.id",
                        "=",
                        "location_service.location_id"
                    )
                    ->geofence(
                        $postcodesIoResult["latitude"],
                        $postcodesIoResult["longitude"],
                        0,
                        $distance,
                        [
                            "table" => "locations",
                        ]
                    )
                    ->orderBy("distance");
            }

            session()->flash("flash.banner", "Postcode not found.");
            session()->flash("flash.bannerStyle", "danger");
            redirect(route("service.index"))->send();
        }
    }
}
