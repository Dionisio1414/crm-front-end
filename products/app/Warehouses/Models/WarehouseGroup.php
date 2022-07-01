<?php

namespace App\Warehouses\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class WarehouseGroup extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'parent_id'];

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

    public function warehouse()
    {
        return $this->hasMany(Warehouse::class, 'warehouse_group_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(WarehouseGroup::class, 'parent_id')->with('children')->orderBy('order', 'ASC');
    }

    public function parent()
    {
        return $this->belongsTo(WarehouseGroup::class, 'parent_id')->with('parent')->orderBy('order', 'ASC');
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
