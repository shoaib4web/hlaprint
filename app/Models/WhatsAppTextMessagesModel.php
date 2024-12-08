<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppTextMessagesModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'message',
        ];
        
    protected $table = 'whatsapp_texts';
}
