<?php

namespace App\Documents\Models;

use App\Directories\Models\Currencies\DirectoryCurrency;
use App\Documents\Models\Purchases\DocumentPurchase;
use App\Documents\Models\TransferStocks\DocumentTransferStock;
use App\Nomenclatures\Models\Nomenclature;
use Illuminate\Database\Eloquent\Model;
use App\Warehouses\Models\Warehouse;
use App\Documents\Models\ReceiptStocks\DocumentReceiptStock;
use App\Documents\Models\WriteOffStocks\DocumentWriteOffStock;
use App\Documents\Models\Orders\DocumentOrder;


class Document extends Model
{
    const NO_CAPITALIZED = 0;
    const CAPITALIZED  = 1;
    const CANCELED_CAPITALIZED  = 2;

    const PACKING = 'уп';

    const RECEIPT_STOCKS = 'receipt_stocks';
    const WRITE_OFF_STOCKS = 'write_off_stocks';
    const TRANSFER_STOCKS = 'transfer_stocks';
    const PURCHASES = 'purchases';
    const ORDERS = 'orders';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['archive', 'status', 'date'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'archive'=>'boolean',
    ];

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'document_warehouse', 'document_id', 'warehouse_id');
    }

    public function document_receipt_stocks()
    {
        return $this->hasOne(DocumentReceiptStock::class, 'document_id');
    }

    public function document_purchases()
    {
        return $this->hasOne(DocumentPurchase::class, 'document_id');
    }

    public function document_write_off_stocks()
    {
        return $this->hasOne(DocumentWriteOffStock::class, 'document_id');
    }

    public function document_transfer_stocks()
    {
        return $this->hasOne(DocumentTransferStock::class, 'document_id');
    }

    public function document_orders()
    {
        return $this->hasOne(DocumentOrder::class, 'document_id');
    }

//    public function document_purchase()
//    {
//        return $this->hasOne(DocumentPurchase::class, 'document_id');
//    }

    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class, 'document_nomenclature', 'document_id', 'nomenclature_id')->withPivot('balance', 'is_packing');
    }

//    public function currencies()
//    {
//        return $this->belongsTo(DirectoryCurrency::class, 'currency_id', 'id');
//    }

    //public $timestamps = false;
}
