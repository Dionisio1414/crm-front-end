<?php

namespace App\Directories\Model;

use Illuminate\Database\Eloquent\Model;


class DirectoriesHeader extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'value'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
        'title' => 'json'
    ];

    protected $with = [
        'items'
    ];

    public function items()
    {
        return $this->hasMany(Directory::class, 'header_id', 'id')->orderBy('directory_id','asc');
    }
}
