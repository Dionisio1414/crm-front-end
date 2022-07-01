<?php

namespace App\Documents\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class DocumentWarehousesDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'header_id', 'document_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'document_id'];

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
