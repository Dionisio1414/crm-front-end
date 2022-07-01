<?php

namespace App\Characteristics\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class CharacteristicValue extends Model
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'characteristic_value';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['characteristic_id', 'title', 'order', 'code', 'edit'];

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
        'edit' => 'boolean',
    ];

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class, 'characteristic_id');
    }

}
