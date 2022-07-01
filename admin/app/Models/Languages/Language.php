<?php

namespace App\Models\Languages;

use App\Helpers\Yesno;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'code', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
