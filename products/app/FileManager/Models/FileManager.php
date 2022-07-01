<?php

namespace App\FileManager\Models;

use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
    protected $table = 'file_manager';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'title', 'path', 'full_path', 'is_image'];

    protected $hidden = ['path', 'full_path', 'created_at'];

    protected $appends = ['url'];

    protected $casts = [
        'is_image' => 'boolean'
    ];

    public function getUrlAttribute()
    {
        return $this->full_path;
    }
}
