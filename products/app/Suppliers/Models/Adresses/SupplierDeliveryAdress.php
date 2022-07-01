<?php

namespace App\Suppliers\Models\Adresses;

use Illuminate\Database\Eloquent\Model;
//use App\Core\Helpers\DirectoryDefaultValue;

class SupplierDeliveryAdress extends Model
{
    protected $table = 'supplier_delivery_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'supplier_id',
        'delivery_address_id',
    ];

    protected $with = ['delivery_address'];

    public function delivery_address()
    {
        return $this->hasOne(SupplierDeliveriesAdress::class, 'id', 'delivery_address_id');
    }

    public $timestamps = false;
}
