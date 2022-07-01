<?php

namespace App\Directories\Models\Currencies;

use App\Core\Helpers\DirectoryDefaultValue;
use Illuminate\Database\Eloquent\Model;

class DirectoryCurrency extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'is_default', 'is_editable', 'is_manual_currency', 'is_download_currency ', 'is_other_currency', 'archive'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'archive'];

    protected $with = ['cells'];

    protected $appends = ['title'];

    protected $casts = [
        'is_default'=>'boolean',
        'is_editable'=>'boolean',
        'archive'=>'boolean',
        'is_manual_currency'=>'boolean',
        'is_download_currency'=>'boolean',
        'is_other_currency'=>'boolean',
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryCurrenciesDetail::class, 'currency_id', 'id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryCurrenciesHeader::class, 'symbol_code');
    }
}
