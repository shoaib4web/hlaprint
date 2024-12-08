<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppRequests extends Model
{
    use HasFactory;
    protected $fillable = [
        'WA_message_id',
        'WA_timestamp',
        'WA_number'
        ];
        
    protected $table = 'whatsapp_requests';
}