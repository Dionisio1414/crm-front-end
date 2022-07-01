<?php

namespace App\Directories\Models\Countries;

use App\Core\Helpers\DirectoryDefaultValue;
use Illuminate\Database\Eloquent\Model;

class DirectoryCountry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'is_default', 'default','is_editable', 'archive'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'archive', 'default'];

    protected $appends = ['title'];

    protected $with = ['cells'];

    protected $casts = [
        'is_default'=>'boolean',
        'is_editable'=>'boolean',
        'archive'=>'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryCountriesDetail::class, 'country_id', 'id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryCountriesHeader::class, 'title_short');
    }
}
