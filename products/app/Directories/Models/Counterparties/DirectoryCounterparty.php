<?php

namespace App\Directories\Models\Counterparties;

use App\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Core\Helpers\DirectoryDefaultValue;

class DirectoryCounterparty extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'archive', 'customer_id', 'supplier_id', 'other_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    protected $with = ['cells'];

    protected $appends = ['title'];

    protected $casts = [
        'archive'=>'boolean',
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryCounterpartiesDetail::class, 'counterparty_id', 'id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryCounterpartiesHeader::class, 'counterparty');
    }
}
