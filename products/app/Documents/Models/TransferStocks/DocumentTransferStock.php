<?php

namespace App\Documents\Models\TransferStocks;

use App\Directories\Models\Currencies\DirectoryCurrency;
//use App\Directories\Models\Employees\DirectoryEmployee;
use App\Directories\Models\Organizations\DirectoryOrganization;
use App\Directories\Models\Users\DirectoryUser;
use App\Documents\Models\Document;
use App\Warehouses\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;

class DocumentTransferStock extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'is_default', 'is_editable', 'document_id', 'warehouse_id', 'to_warehouse_id', 'currency_id', 'responsible_id', 'comments', 'archive', 'receipt_date_actual', 'receipt_date_scheduled', 'organization_id', 'organization_convert_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $with = ['cells', 'currency', 'responsible' ,'warehouse', 'organization', 'document', 'delivery'];

    protected $casts = [
        'is_default' => 'boolean',
        'is_editable' => 'boolean',
        'archive' => 'boolean',
    ];

    public function cells()
    {
        return $this->hasMany(DocumentTransferStocksDetail::class, 'transfer_stock_id', 'id');
    }

    public function delivery()
    {
        return $this->hasOne(DocumentTransferStocksDelivery::class, 'document_transfer_stock_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(DirectoryCurrency::class, 'currency_id', 'id');
    }

    public function responsible()
    {
        return $this->belongsTo(DirectoryUser::class, 'responsible_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function to_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(DirectoryOrganization::class, 'organization_id', 'id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
