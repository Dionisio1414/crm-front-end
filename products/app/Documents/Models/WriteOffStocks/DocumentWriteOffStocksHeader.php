<?php

namespace App\Documents\Models\WriteOffStocks;

use Illuminate\Database\Eloquent\Model;

class DocumentWriteOffStocksHeader extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'is_sortable', 'is_visible', 'value', 'order'];

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
        'title' => 'json',
        'is_sortable' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public $timestamps = false;
}
