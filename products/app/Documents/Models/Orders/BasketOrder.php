<?php

namespace App\Documents\Models\Orders;

use App\Directories\Models\Users\DirectoryUser;
use App\Nomenclatures\Models\Nomenclature;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class BasketOrder extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'nomenclature_id',
        'count'
    ];

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
    protected $casts = [];

    public function nomenclatures()
    {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id', 'id');
    }
//
//    public function nomenclature()
//    {
//        return $this->hasOne(Nomenclature::class, 'id', 'nomenclature_id');
//    }
    public function users()
    {
        return $this->belongsTo(DirectoryUser::class, 'user_id', 'gateway_user_id');
    }
}
