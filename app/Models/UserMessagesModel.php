<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMessagesModel extends Model
{
    use HasFactory;
    protected $table = 'user_messages';
    protected $fillable = [
        'message_id',
        'conversation_id',
        'message',
    ];
}
