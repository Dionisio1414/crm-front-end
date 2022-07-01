<?php

namespace App\Directories\Models\Cities;

use App\Core\Helpers\DirectoryDefaultValue;
use App\Directories\Models\Regions\DirectoryRegion;
use Illuminate\Database\Eloquent\Model;

class DirectoryCity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'region_id', 'zip_code', 'is_default', 'is_editable', 'archive'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['title', 'region', 'created_at', 'updated_at', 'archive'];

    protected $appends = ['country_id', 'title'];

    protected $with = ['cells'];

    protected $casts = [
        'is_default'=>'boolean',
        'is_editable'=>'boolean',
        'boolean'=>'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryCitiesDetail::class, 'city_id', 'id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryCitiesHeader::class, 'city');
    }

    public function region()
    {
        return $this->hasOne(DirectoryRegion::class, 'id', 'region_id');
    }

    public function getCountryIdAttribute()
    {
        return $this->region->country_id ?? null;
    }
}
