<?php

namespace App\Warehouses\Models;

use App\Directories\Models\Cities\DirectoryCity;
use App\Directories\Models\Countries\DirectoryCountry;
use App\Directories\Models\Employees\DirectoryEmployee;
use App\Documents\Models\Document;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Nomenclatures\Models\Nomenclature;

class Warehouse extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'phone',
        'street',
        'number_house',
        'country_id',
        'archive',
        'order',
        'region_id',
        'city_id',
        'warehouse_type_id',
        'warehouse_group_id',
        'employee_id',
        'is_default'
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
    protected $casts = [];

    public function warehouseGroups()
    {
        return $this->belongsTo(WarehouseGroup::class, 'warehouse_group_id', 'id');
    }

    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class, 'nomenclature_warehouse', 'warehouse_id', 'nomenclature_id')->withPivot(['balance', 'reserve']);
    }

    public function countries()
    {
        return $this->belongsTo(DirectoryCountry::class, 'country_id', 'id');
    }

    public function regions()
    {
        return $this->belongsTo(DirectoryCity::class, 'region_id', 'id');
    }

    public function cities()
    {
        return $this->belongsTo(DirectoryCity::class, 'city_id', 'id');
    }

    public function employees()
    {
        return $this->belongsTo(DirectoryEmployee::class, 'employee_id', 'id');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_warehouse', 'warehouse_id', 'document_id');
    }

    public function setPhoneAttribute($phone)
    {
        if (is_array($phone)) {
            $this->attributes['phone'] = json_encode($phone);
        }
    }

    public function getPhoneAttribute($phone)
    {
        return json_decode($phone, true);
    }

//    public function categories()
//    {
//        return $this->belongsToMany(Category::class, 'category_property', 'property_id', 'category_id');
//    }
//
//    public function propertyValue()
//    {
//        return $this->hasMany(PropertyValue::class);
//    }
}
