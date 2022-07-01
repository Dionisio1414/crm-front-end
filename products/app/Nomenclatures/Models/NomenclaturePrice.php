<?php

namespace App\Nomenclatures\Models;

use App\Directories\Models\TypesPrices\DirectoryTypesPrice;
use App\Directories\Models\Units\DirectoryUnit;
use App\Documents\Models\Document;
use App\Suppliers\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class NomenclaturePrice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const DOCUMENTS = 'documents';
    const CATEGORIES  = 'categories';
    const PRICES  = 'prices';
    const STOCKS  = 'stocks';
    const NOMENCLATURES  = 'nomenclatures';

    protected $fillable = ['nomenclature_id', 'price_id', 'value', 'percent', 'document_id', 'unit_id', 'supplier_id', 'date', 'status'];

    protected $table = 'nomenclature_price';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public $timestamps = false;

    public function nomenclature()
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id');
    }

    public function price()
    {
        return $this->belongsTo(DirectoryTypesPrice::class, 'price_id');
    }

    public function units()
    {
        return $this->belongsTo(DirectoryUnit::class, 'unit_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
