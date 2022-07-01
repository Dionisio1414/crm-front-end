<?php

namespace App\Chats\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ChatMessageStatus extends Model
{
    use Notifiable;

    protected $table = 'chat_message_status';

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
    protected $casts = [];


    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'message_id', 'id');
    }

}
