<?php

namespace App\Directories\Models\CounterpartiesReturns;

use App\Core\Helpers\DirectoryDefaultValue;
use Illuminate\Database\Eloquent\Model;

class DirectoryCounterpartiesReturn extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'is_default', 'is_editable', 'archive'];

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
        'archive'=>'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryCounterpartiesReturnsDetail::class, 'return_id', 'id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryCounterpartiesReturnsHeader::class, 'title');
    }
}
