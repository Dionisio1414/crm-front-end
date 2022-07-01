<?php

namespace App\Characteristics\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class CharacteristicSizeValue extends Model
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'characteristic_size_value';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['characteristic_id', 'title', 'order', 'type', 'check'];

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
        'check' => 'boolean',
    ];

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class, 'characteristic_id');
    }
}
