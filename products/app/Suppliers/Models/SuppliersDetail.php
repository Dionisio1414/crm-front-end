<?php

namespace App\Suppliers\Models;

use Illuminate\Database\Eloquent\Model;

class SuppliersDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'header_id', 'supplier_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'supplier_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'json'
    ];

    public $timestamps = false;
}
