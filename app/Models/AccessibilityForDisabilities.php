<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class AccessibilityForDisabilities extends Model
{
    use HasFactory;
    use UuidTrait;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["location_id", "accessibility"];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
