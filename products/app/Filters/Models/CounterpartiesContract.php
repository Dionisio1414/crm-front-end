<?php

namespace App\Filters\Models;

use Illuminate\Database\Eloquent\Model;

class CounterpartiesContract extends Model
{
    protected $table = 'directory_counterparties_contracts_filter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'selects_type_contracts_id',
        'periods_period_date_from',
        'periods_period_date_to',
        'lists_organizations_ids',
        'lists_price_types_ids',
        'lists_currencies_ids',
        'booleans_is_contract_signed',
        'booleans_is_status_contract',
        'ranges_due_date_from',
        'ranges_due_date_to',
        'ranges_percent_from',
        'ranges_percent_to',
        'is_active_filter',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    protected $with = [];

    protected $casts = [
        'lists_organizations_ids'=>'array',
        'lists_price_types_ids'=>'array',
        'lists_currencies_ids'=>'array',
        'booleans_is_contract_signed' => 'boolean',
        'booleans_is_status_contract' => 'boolean',
        'is_active_filter' => 'boolean'
    ];
}
