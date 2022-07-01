<?php

namespace App\Nomenclatures\Models;

use App\Directories\Models\Units\DirectoryUnit;
use App\Directories\Models\Countries\DirectoryCountry;
use App\Properties\Models\PropertyValue;
use App\Characteristics\Models\CharacteristicValue;
use App\Characteristics\Models\CharacteristicColorValue;
use App\Suppliers\Models\Supplier;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'short_title',
        'dock_title',
        'desc',
        'sku',
        'lower_limit',
        'min_price',
        'weight',
        'volume',
        'general_count',
        'reserve',
        'barcode_supplier',
        'barcode',
        'packing',
        'nomenclature_id',
        'unit_id',
        'manufacturer_id',
        'supplier_id',
        'is_groups',
        'characteristic_value_id',
        'characteristic_color_value_id',
        'identifier_fullness',
        'identifier_successful',
        'country_id',
        'weight_id',
        'volume_id',
        'characteristic_value_id',
        'characteristic_color_value_id',
        'brand',
        'model',
        'convert_id'
    ];

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
    //protected $casts = [];
    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
        'updated_at' => 'datetime:d.m.Y',
        'is_groups' => 'boolean',
    ];

//    public function suppliers()
//    {
//        return $this->belongsTo(DirectoryUnit::class, 'supplier_id', 'id');
//    }

    public function units()
    {
        return $this->belongsTo(DirectoryUnit::class, 'unit_id', 'id');
    }

    public function volumes()
    {
        return $this->belongsTo(DirectoryUnit::class, 'volume_id', 'id');
    }

    public function weights()
    {
        return $this->belongsTo(DirectoryUnit::class, 'weight_id', 'id');
    }

    public function countries()
    {
        return $this->belongsTo(DirectoryCountry::class, 'country_id', 'id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function nomenclature()
    {
        return $this->hasOne(Nomenclature::class, 'nomenclature_id');
    }

    public function nomenclatures()
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id');
    }



//    public function baseCharacteristicValue()
//    {
//        return $this->belongsTo(CharacteristicValue::class, 'characteristic_value_id', 'id');
//    }
//
//    public function baseCharacteristicColorValue()
//    {
//        return $this->belongsTo(CharacteristicColorValue::class, 'characteristic_color_value_id', 'id');
//    }
//
//    public function setDockTitleAttribute($dock_title)
//    {
//        if (is_array($dock_title)) {
//            $this->attributes['dock_title'] = json_encode($dock_title);
//        }
//    }
//
//    public function getDockTitleAttribute($dock_title)
//    {
//        return json_decode($dock_title, true);
//    }

}
