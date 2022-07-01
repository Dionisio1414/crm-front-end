<?php

namespace App\Directories\Models\Positions;

use Illuminate\Database\Eloquent\Model;

class DirectoryPositionsDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'header_id', 'position_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'position_id'];

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
