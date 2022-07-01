<?php

namespace App\Products\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use Notifiable;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'desc'];

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
}
