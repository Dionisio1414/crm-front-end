<?php

namespace App\Nomenclatures\Models;

use App\Categories\Models\Category;
use App\Characteristics\Models\Characteristic;
use App\Characteristics\Models\CharacteristicColorValue;
use App\Characteristics\Models\CharacteristicValue;
use App\Core\Model\Yesno;
use App\Documents\Models\Document;
use App\Documents\Models\Orders\BasketOrder;
use App\FileManager\Models\FileManager;
use App\Properties\Models\PropertyValue;
//use App\Directories\Models\Prices\DirectoryPrice;
use App\Warehouses\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use App\Directories\Models\TypesPrices\DirectoryTypesPrice;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    use Notifiable;

    const PRODUCT = 'product';
    const SERVICE  = 'service';
    const KIT  = 'kit';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'order', 'archive', 'is_actual', 'is_visible', 'group_id'];

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
    protected $casts = [
        'archive' => 'boolean',
        'is_actual' => 'boolean',
        'is_visible' => 'boolean',
    ];

//    public function categories()
//    {
//        return $this->belongsTo(Category::class,'category_id');
//    }
//
//    public function nomenclatures()
//    {
//        return $this->hasMany(Category::class,'category_id');
//    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('archive_is_actual', function (Builder $builder) {
            $builder->where('archive', Yesno::NO)->where('is_actual', Yesno::YES)->orderBy('order', 'ASC');
        });
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'nomenclature_id');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_nomenclature','nomenclature_id', 'document_id' )->withPivot('balance', 'is_packing');
    }

    public function service()
    {
        return $this->hasOne(Service::class, 'nomenclature_id');
    }

    public function kit()
    {
        return $this->hasOne(Kit::class, 'nomenclature_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function propertyValue()
    {
        return $this->belongsToMany(PropertyValue::class, 'nomenclature_property_value', 'nomenclature_id', 'property_value_id');
    }

    public function characteristic()
    {
        return $this->belongsToMany(Characteristic::class, 'characteristic_nomenclature', 'nomenclature_id', 'characteristic_id')->withPivot('is_base');
    }

    public function characteristicValue()
    {
        return $this->belongsToMany(CharacteristicValue::class, 'nomenclature_characteristic_value', 'nomenclature_id', 'characteristic_value_id')->withPivot('is_base');
    }

    public function characteristicColorValue()
    {
        return $this->belongsToMany(CharacteristicColorValue::class, 'nomenclature_characteristic_color_value', 'nomenclature_id', 'characteristic_color_value_id')->withPivot('is_base');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_nomenclature', 'nomenclature_id', 'category_id');
    }

    public function prices()
    {
        return $this->belongsToMany(DirectoryTypesPrice::class, 'nomenclature_price', 'nomenclature_id', 'price_id')
            ->withPivot('value', 'id', 'unit_id', 'supplier_id', 'date', 'status', 'document_id', 'percent')
            ->orderBy('date', 'DESC');
    }

    public function groups()
    {
        return $this->hasMany(Nomenclature::class, 'group_id');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'nomenclature_warehouse', 'nomenclature_id', 'warehouse_id')->withPivot(['balance', 'reserve']);
    }

    public function related()
    {
        return $this->belongsToMany(Nomenclature::class, 'nomenclature_related', 'nomenclature_id', 'related_id')->orderBy('id', 'DESC');
    }

    public function kits()
    {
        return $this->belongsToMany(Nomenclature::class, 'nomenclature_kit', 'nomenclature_id', 'kit_id')->withPivot(['count'])->orderBy('id', 'DESC');
    }

    public function analog()
    {
        return $this->belongsToMany(Nomenclature::class, 'nomenclature_analog', 'nomenclature_id', 'analog_id')->orderBy('id', 'DESC');
    }

    public function basketOrders()
    {
        return $this->hasOne(BasketOrder::class, 'nomenclature_id');
    }

    public function files()
    {
        return $this->belongsToMany(FileManager::class, 'file_nomenclature', 'nomenclature_id', 'file_id');
    }

//    public function prices()
//    {
//        return $this->belongsToMany(DirectoryPrice::class, 'nomenclature_price', 'nomenclature_id', 'price_id');
//    }

}
