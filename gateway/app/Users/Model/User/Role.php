<?php
namespace App\Users\Model\User;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'user_role';
    
    protected $fillable = [
        'title', 'resources'
    ];

    protected $casts = [
        'resources' => 'array'
    ];

    public function isAvailable($route)
    {
        return in_array($route, $this->resources);
    }

}
