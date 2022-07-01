<?php

namespace App\Documents\Models\TransferStocks;

use Illuminate\Database\Eloquent\Model;

class DocumentTransferStocksDelivery extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_transfer_stock_id',
        'delivery_methods_id',
        'type_deliveries_id',
        'department_city_id',
        'department_id',
        'ttn_number',
        'ttn_date',
        'is_power_of_attorney',
        'time_power_of_attorney',
        'number_power_of_attorney',
        'confidant_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'id', 'document_transfer_stock_id'];

    protected $with = [];

    protected $casts = [
        'is_power_of_attorney' => 'boolean'
    ];
}
