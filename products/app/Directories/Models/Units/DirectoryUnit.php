<?php

namespace App\Directories\Models\Units;

use Illuminate\Database\Eloquent\Model;
use App\Core\Helpers\DirectoryDefaultValue;

class DirectoryUnit extends Model
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
        return $this->hasMany(DirectoryUnitsDetail::class, 'unit_id', 'id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryUnitsHeader::class, 'title_short');
    }
}
