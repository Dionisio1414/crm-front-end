<?php

namespace App\Directories\Models\CounterpartiesContracts;

use App\Directories\Models\Organizations\DirectoryOrganization;
use App\Directories\Models\TypesPrices\DirectoryTypesPrice;
use App\Directories\Models\Currencies\DirectoryCurrency;
use App\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class DirectoryCounterpartiesContract extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'is_default',
        'is_automatically',
        'archive',
        'title',
        'registration_number',
        'contract_date',
        'from_period_date',
        'to_period_date',
        'supplier_id',
        'customer_id',
        'other_id',
        'type_contract_id',
        'organization_id',
        'currency_id',
        'price_type_id',
        'is_contract_signed',
        'status_contract_id',
        'description',
        'due_date',
        'percent',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at'];

    protected $with = ['cells'];

    protected $casts = [
        'is_automatically'=>'boolean',
        'archive'=>'boolean',
        'is_contract_signed'=>'boolean',
        'contract_date'=>'datetime:d.m.Y'
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryCounterpartiesContractsDetail::class, 'counterparty_contract_id', 'id');
    }

    public function organization()
    {
        return $this->hasOne(DirectoryOrganization::class, 'id','organization_id');
    }

    public function currency()
    {
        return $this->hasOne(DirectoryCurrency::class, 'id','currency_id');
    }

    public function price_type()
    {
        return $this->hasOne(DirectoryTypesPrice::class, 'id','price_type_id');
    }

    public function counterparty_supplier()
    {
        return $this->hasOne(Supplier::class, 'id','supplier_id');
    }

//    public function counterparty_customer()
//    {
//        return $this->hasOne(DirectoryTypesPrice::class, 'id','price_type_id');
//    }
//
//    public function counterparty_other()
//    {
//        return $this->hasOne(DirectoryTypesPrice::class, 'id','price_type_id');
//    }
}
