<?php

namespace App\Properties\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Categories\Models\Category;

class Property extends Model
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'order'];

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

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_property', 'property_id', 'category_id');
    }

    public function propertyValue()
    {
        return $this->hasMany(PropertyValue::class);
    }
}
