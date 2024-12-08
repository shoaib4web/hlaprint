<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'WA_timestamp',
        'WA_message_id',
        'WA_number',
        ];

    protected $table = 'whatsapp_requests';
}
