<?php

namespace App\Characteristics\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Categories\Models\Category;

class Characteristic extends Model
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'order', 'archive'];

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
        return $this->belongsToMany(Category::class, 'category_characteristic', 'characteristic_id', 'category_id');
    }

    public function characteristicValue()
    {
        return $this->hasMany(CharacteristicValue::class);
    }

    public function characteristicColorValue()
    {
        return $this->hasMany(CharacteristicColorValue::class);
    }

    public function characteristicSizeValue()
    {
        return $this->hasMany(CharacteristicSizeValue::class);
    }
}
