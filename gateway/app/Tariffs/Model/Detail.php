<?php

namespace App\Tariffs\Model;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $table = 'detail_tariffs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'tariff_id', 'language_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
