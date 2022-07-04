<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Builder;

class RegularSchedule extends Model
{
    use HasFactory;
    use UuidTrait;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("order", function (Builder $builder) {
            $builder->orderBy("weekday", "asc");
        });
    }

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "service_id",
        "location_id",
        "weekday",
        "opens_at",
        "closes_at",
        "valid_from",
        "valid_to",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "valid_from" => "date",
        "valid_to" => "date",
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
