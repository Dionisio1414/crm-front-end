<?php

namespace App\Models\Users;

use App\Helpers\Yesno;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    protected $userService;

    public function __construct(array $attributes = array())
    {
        $this->userService = new UserService();

        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'role_id',
        'email',
        'phone',
        'first_name',
        'last_name',
        'verification_token',
        'password'
    ];

    protected $appends = ['is_owner'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'verification_token'
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function getIsOwnerAttribute()
    {
        if($this->id == optional($this->company)->owner_id){
            return Yesno::YES;
        }

        return Yesno::NO;
    }

    public function insertAndSetId(Builder $query, $attributes)
    {
        $data['data'] = $attributes;
        $data['data']['from_admin'] = Yesno::YES;
        $user = $this->userService->registerCompany($data);

        $this->setAttribute($this->getKeyName(), $user->user->id ?? 0);
    }

    public function delete()
    {
        if(optional($this->company)->owner_id != $this->id){
            parent::delete();
        }

        /* Remove company */
        if(optional($this->company)->id){
            $response = $this->userService->deleteCompany($this->company->id);
            if($response){
                parent::delete();
            }
        }
    }
}
