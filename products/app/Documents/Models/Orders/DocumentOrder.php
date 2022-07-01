<?php

namespace App\Documents\Models\Orders;

use App\Directories\Models\Counterparties\DirectoryCounterparty;
use App\Directories\Models\CounterpartiesCancellations\DirectoryCounterpartiesCancellation;
use App\Directories\Models\CounterpartiesCancellations\DirectoryCounterpartiesCancellationsDetail;
use App\Directories\Models\CounterpartiesReturns\DirectoryCounterpartiesReturn;
use App\Directories\Models\CounterpartiesReturns\DirectoryCounterpartiesReturnsDetail;
use App\Directories\Models\Currencies\DirectoryCurrency;
use App\Directories\Models\TypesPrices\DirectoryTypesPrice;
use App\Directories\Models\Users\DirectoryUser;
use App\Documents\Models\Document;
use App\Suppliers\Models\Supplier;
use App\Warehouses\Models\Warehouse;
use App\Directories\Models\Organizations\DirectoryOrganization;
use Illuminate\Database\Eloquent\Model;

class DocumentOrder extends Model
{

    const STATUS_SHIPMENT  = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'organization_convert_id',
        'organization_id',
        'document_id' ,
        'warehouse_id',
        'supplier_id',
        'contract_id',
        'currency_id',
        'price_id',
        'status_order_id',
        'status_payment_id',
        'receipt_date_scheduled',
        'payment_date_scheduled',
        'payment_date_actual',
        'receipt_date_actual',
        'terms_payment_id',
        'form_payment_id',
        'directory_cancell_id',
        'directory_return_id',
        'supplier_document_date',
        'supplier_document_number',
        'description',
        'create_document_date',
        'responsible',
        'document_amount',
        'archive',
        'counterparty_id',
        'is_reserve',
        'is_discount',
        'shipment_date_scheduled',
        'shipment_date_actual',
        'responsible_id',
        'comments'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['status'];

    protected $with = ['cells' , 'delivery', 'supplier', 'currency', 'counterparty', 'responsible'];

    protected $appends = ['manager', 'status'];

    protected $casts = [
        'archive' => 'boolean',
        'is_reserve' => 'boolean',
        'is_discount' => 'boolean'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }

    public function cells()
    {
        return $this->hasMany(DocumentOrdersDetail::class, 'order_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(DirectoryCurrency::class, 'currency_id', 'id');
    }

    public function counterparty()
    {
        return $this->belongsTo(DirectoryCounterparty::class, 'counterparty_id', 'id');
    }

    public function price()
    {
        return $this->hasOne(DirectoryTypesPrice::class, 'price_id', 'id');
    }

    public function delivery()
    {
        return $this->hasOne(DocumentOrdersDelivery::class, 'document_order_id', 'id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function organization()
    {
        return $this->hasOne(DirectoryOrganization::class, 'id', 'organization_id');
    }

    public function responsible()
    {
        return $this->belongsTo(DirectoryUser::class, 'responsible_id', 'id');
    }

    public function directory_return()
    {
        return $this->hasOne(DirectoryCounterpartiesReturnsDetail::class, 'return_id', 'directory_return_id');
    }

    public function directory_cancell()
    {
        return $this->hasOne(DirectoryCounterpartiesCancellationsDetail::class, 'cancel_id', 'directory_cancell_id');
    }

    public function getManagerAttribute()
    {
        return $this->supplier->title_supplier ?? null;
    }

    public function getStatusAttribute()
    {
        return $this->document->status;
    }

}
