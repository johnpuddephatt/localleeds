<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Relations\Pivot;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Image;
use Storage;

class Organisation extends Model
{
    use HasFactory, SoftDeletes;
    use UuidTrait;

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value
                ? Storage::disk("public")->url($value)
                : null,
            set: function ($value) {
                if ($value) {
                    $logo = Image::make($value)
                        ->resize(250, 250)
                        ->encode("png", 80);
                    $logo_name = Str::random(12) . ".png";
                    Storage::disk("public")->put($logo_name, $logo);
                    return $logo_name;
                }
            }
        );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["name", "description", "email", "url", "logo"];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function types()
    {
        return $this->morphToMany(Taxonomy::class, "link", "link_taxonomy");
    }
}
