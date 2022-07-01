<?php

namespace App\Chats\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

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
        'is_group_chat'=>'boolean',
    ];

    const UPDATE_CHAT = 'update chat';
    const CREATE_GROUP_CHAT = 'create group chat';
    const CREATE_PRIVATE_CHAT = 'create privat chat';
    const ADDED_USERS_TO_CHAT = 'added_users_to_chat';

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id');
    }

    public function message()
    {
        return $this->hasOne(ChatMessage::class, 'chat_id', 'id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'chat_id', 'id')->latest();
    }

    public function users()
    {
        return $this->hasMany(ChatUser::class, 'chat_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(ChatUser::class, 'chat_id', 'id');
    }

}
