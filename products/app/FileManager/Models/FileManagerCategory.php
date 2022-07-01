<?php

namespace App\FileManager\Models;

use Illuminate\Database\Eloquent\Model;

class FileManagerCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'title'];

    protected $hidden = ['created_at'];
}
