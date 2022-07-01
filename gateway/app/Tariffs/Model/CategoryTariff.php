<?php

namespace App\Tariffs\Model;

use Illuminate\Database\Eloquent\Model;


class CategoryTariff extends Model
{

    protected $fillable = ['title', 'parent_category', 'tooltip_info'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    protected $with = ['detail'];

    public function detail()
    {
        return $this->hasMany(DetailCategoryTariff::class, 'category_tariff_id', 'id');
    }
}
