<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class Contact extends Model
{
    use HasFactory;
    use UuidTrait;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["service_id", "organisation_id", "name", "title"];

    public function phone()
    {
        return $this->hasOne(Phone::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
