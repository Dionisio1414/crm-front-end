<?php

namespace App\Directories\Models\TypesPrices;

use App\Directories\Models\Currencies\DirectoryCurrenciesHeader;
use App\Directories\Models\Currencies\DirectoryCurrency;
use Illuminate\Database\Eloquent\Model;
use App\Core\Helpers\DirectoryDefaultValue;

class DirectoryTypesPrice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const CURRENT_PRICE = [["code" => "ru", "title" => "Действ.цена"], ["code" => "ua", "title" => "Действ.цена"]];
    const VARIANCE_PRICE = [["code" => "ru", "title" => "Отклонение"], ["code" => "ua", "title" => "Відхилення"]];
    const NEW_PRICE = [["code" => "ru", "title" => "Новая цена"], ["code" => "ua", "title" => "Нова ціна"]];

    protected $fillable = [
        'id',
        'core_title',
        'title_price_list',
        'is_default',
        'default',
        'is_editable',
        'is_rounding_without',
        'is_rounding_with',
        'is_step_with',
        'is_buy',
        'core_currency_id',
        'core_type_price_id',
        'core_type_price_calculate_margin_percent_id',
        'core_title_price_list',
        'margin_percent',
        'archive'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'archive', 'default'];

    protected $with = ['cells'];

    protected $appends = ['title', 'currency_id', 'type_price_id', 'symbol_currency', 'title_price_list'];

    protected $casts = [
        'is_default' => 'boolean',
        'is_editable' => 'boolean',
        'archive' => 'boolean',
        'is_rounding_without' => 'boolean',
        'is_rounding_with' => 'boolean',
        'is_buy' => 'boolean',
        'core_title_price_list' => 'array',
        'core_title' => 'array',
    ];

    public function cells()
    {
        return $this->hasMany(DirectoryTypesPricesDetail::class, 'type_price_id', 'id');
    }

    public function getTitleAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryTypesPricesHeader::class, 'title');
    }

    public function getTitlePriceListAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryTypesPricesHeader::class, 'title_price_list');
    }

    public function getCurrencyIdAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryTypesPricesHeader::class, 'currency');
    }

    public function getTypePriceIdAttribute()
    {
        return DirectoryDefaultValue::getDefaultTitle($this->cells, DirectoryTypesPricesHeader::class, 'type_price');
    }

    public function getSymbolCurrencyAttribute()
    {
        $currency = $this->hasOne(DirectoryCurrency::class, 'id', 'core_currency_id')->first();
        if (isset($currency['cells'])) {
            return DirectoryDefaultValue::getDefaultTitleArray($currency['cells']->toArray(), DirectoryCurrenciesHeader::class, 'symbol');
        }

        return null;
    }

    public function getCurrencyAttribute()
    {
        return $this->hasOne(DirectoryCurrency::class, 'id', 'core_currency_id')->first();
    }

    public function getTypePriceAttribute()
    {
        return $this->hasOne(DirectoryTypesPrice::class, 'id', 'core_type_price_id')->first();
    }

    public function dependentPrices()
    {
        return $this->hasMany(DirectoryTypesPrice::class, 'core_type_price_calculate_margin_percent_id');
    }

}
