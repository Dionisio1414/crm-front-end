<?php

namespace App\Properties\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class PropertyValue extends Model
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'property_value';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['property_id', 'title', 'order'];

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
        'property_id' => 'int',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
