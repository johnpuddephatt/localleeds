<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class CostOption extends Model
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
        "valid_from",
        "valid_to",
        "option",
        "amount",
        "amount_description",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "valid_from" => "date",
        "valid_to" => "date",
        "amount" => "integer",
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
