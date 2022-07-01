<?php

namespace App\Tariffs\Model;

use App\Core\Model\Yesno;
use Illuminate\Database\Eloquent\Model;

class CustomColumn extends Model
{
    protected $table = 'custom_columns_tariffs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = ['title' => 'json'];
}
