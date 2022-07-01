<?php

namespace App\Characteristics\Models;

use App\FileManager\Models\FileManager;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class CharacteristicColorValue extends Model
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'characteristic_color_value';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['characteristic_id', 'title', 'order', 'code', 'color', 'image_id'];

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
    protected $with = ['image'];

    protected $casts = [];

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class, 'characteristic_id');
    }

    public function image()
    {
        return $this->belongsTo(FileManager::class, 'image_id');
    }
}
