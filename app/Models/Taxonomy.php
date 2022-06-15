<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class Taxonomy extends Model
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
        "parent_id",
        "name",
        "vocabulary",
        "type",
        "label",
        "featured",
    ];

    public function taxonomies()
    {
        return $this->hasMany(Taxonomy::class);
    }

    public function parent()
    {
        return $this->belongsTo(Taxonomy::class);
    }
}
