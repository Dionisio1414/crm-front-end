<?php

namespace App\Documents\Models\Purchases;

use App\Directories\Models\CounterpartiesCancellations\DirectoryCounterpartiesCancellation;
use App\Directories\Models\CounterpartiesCancellations\DirectoryCounterpartiesCancellationsDetail;
use App\Directories\Models\CounterpartiesReturns\DirectoryCounterpartiesReturn;
use App\Directories\Models\CounterpartiesReturns\DirectoryCounterpartiesReturnsDetail;
use App\Directories\Models\Currencies\DirectoryCurrency;
use App\Documents\Models\Document;
use App\Suppliers\Models\Supplier;
use App\Warehouses\Models\Warehouse;
use App\Directories\Models\Organizations\DirectoryOrganization;
use Illuminate\Database\Eloquent\Model;

class DocumentPurchase extends Model
{
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
        'status_product_id',
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
        'document_type',
        'archive'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['status', 'document_type'];

    protected $with = ['cells' , 'delivery', 'supplier', 'currency'];

    protected $appends = ['manager','status'];

    protected $casts = [
        'archive' => 'boolean'
    ];

    public function cells()
    {
        return $this->hasMany(DocumentPurchasesDetail::class, 'purchase_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(DirectoryCurrency::class, 'currency_id', 'id');
    }

    public function delivery()
    {
        return $this->hasOne(DocumentPurchasesDelivery::class, 'document_purchase_id', 'id');
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

    public function document()
    {
        return $this->hasOne(Document::class, 'id', 'document_id');
    }

    public function getStatusAttribute()
    {
        return $this->document->status;
    }
}
