<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationsModel extends Model
{
    use HasFactory;
    protected $table = 'conversations';
    protected $fillable = [
        'conversation_id',
        'phone',
        'first_message_date',
        'last_message_date',
        'last_message_time',
        'last_message',
        ];
        
    
}
