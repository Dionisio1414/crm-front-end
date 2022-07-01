<?php

namespace App\Leads\Models;

use Illuminate\Database\Eloquent\Model;

class LeadsStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'title_full', 'background_color'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   // protected $hidden = ['id', 'lead_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public $timestamps = false;
}
