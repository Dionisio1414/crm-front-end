<?php
namespace App\Models\Users;

use App\Helpers\Yesno;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
    protected $userService;

    public function __construct(array $attributes = array())
    {
        $this->userService = new UserService();

        parent::__construct($attributes);
    }

    protected $table = 'companies';

    protected $fillable = ['owner_id', 'name', 'domain', 'db_database', 'db_username', 'db_password'];

    protected $appends = ['fio'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'owner_id'
    ];

    protected $with = ['user'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function getFioAttribute()
    {
        return sprintf('%s %s %s', $this->user->last_name, $this->user->first_name, $this->user->middle_name);
    }

    public function save(array $options = [])
    {
        $data = [
            'name' => $this->name,
            'domain' => $this->domain,
            'from_admin' => Yesno::YES,
        ];
        $this->userService->updateCompany($this->id, $data);

        parent::save($data);
    }
}
