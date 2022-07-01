<?php

namespace App\Tariffs\Model;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    protected $fillable = ['tariff_id', 'user_id', 'type', 'start_subscription', 'end_subscription', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
}
