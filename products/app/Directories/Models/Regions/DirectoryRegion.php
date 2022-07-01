<?php

namespace App\Directories\Models\Regions;

use App\Directories\Models\Countries\DirectoryCountriesDetail;
use App\Directories\Models\Countries\DirectoryCountry;
use App\Directories\Models\Regions\DirectoryRegionsDetail;
use Database\Seeders\Company\DirectoryCountries;
use Illuminate\Database\Eloquent\Model;
use App\Core\Helpers\DirectoryDefaultValue;

class DirectoryRegion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'country_id', 'is_default', 'is_editable', 'archive'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['title', 'created_at', 'updated_at', 'archive'];

    protected $with = ['cells'];

    protected $appends = ['title'];

    protected $casts = [
        'is_default'=>'boolean',
        'is_editable'=>'boolean',
        'archive'=>'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryRegionsDetail::class, 'region_id', 'id');
    }

    public function country()
    {
        return $this->hasOne(DirectoryCountry::class, 'id', 'country_id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryRegionsHeader::class, 'title');
    }
}
