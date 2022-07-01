<?php

namespace App\Chats\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
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
    protected $casts = [
        'params' => 'json'
    ];

    const EDIT = 'edit';
    const DELETE = 'delete';
    const READ = 'read';

    public function messagesStatus()
    {
        return $this->hasMany(ChatMessageStatus::class, 'message_id', 'id');
    }

    public function messageStatus()
    {
        return $this->hasOne(ChatMessageStatus::class, 'message_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(ChatUser::class, 'chat_id', 'chat_id');
    }

    public function setParamsAttribute($params)
    {
        if (is_array($params)) {
            $this->attributes['params'] = json_encode($params);
        }
    }

    public function getParamsAttribute($params)
    {
        return json_decode($params, true);
    }
}
