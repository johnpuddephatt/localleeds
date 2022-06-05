<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class Review extends Model
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
        "service_id",
        "reviewer_organisation_id",
        "title",
        "description",
        "date",
        "score",
        "url",
        "widget",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "date" => "date",
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function reviewerOrganisation()
    {
        return $this->belongsTo(ReviewerOrganisation::class);
    }
}
