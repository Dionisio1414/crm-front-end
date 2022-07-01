<?php

namespace App\Directories\Models\CounterpartiesCancellations;

use App\Core\Helpers\DirectoryDefaultValue;
use Illuminate\Database\Eloquent\Model;

class DirectoryCounterpartiesCancellation extends Model
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

    protected $appends = ['title'];

    protected $with = ['cells'];

    protected $casts = [
        'is_default'=>'boolean',
        'is_editable'=>'boolean',
        'archive'=>'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryCounterpartiesCancellationsDetail::class, 'cancel_id', 'id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryCounterpartiesCancellationsHeader::class, 'title');
    }
}
