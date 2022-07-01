<?php

namespace App\Tariffs\Model;

use Illuminate\Database\Eloquent\Model;


class DetailCategoryTariff extends Model
{

    protected $fillable = ['value', 'category_tariff_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
}
