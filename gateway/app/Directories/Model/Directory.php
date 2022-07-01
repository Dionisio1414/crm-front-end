<?php

namespace App\Directories\Model;

use Illuminate\Database\Eloquent\Model;
//use App\Core\Model\Yesno;

class Directory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['directory_id', 'title', 'header_id', 'thumbnail'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'header_id',
        'id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'title' => 'json',
        'directory_id' => 'int'
    ];

    protected function getThumbnailAttribute($value)
    {
        if($value){
            return env('HTTP_SHEMA', 'https://') . 'gateway.' .env('MAIN_DOMAIN') . '/storage/' . $value;
        }

        return null;
    }
}
