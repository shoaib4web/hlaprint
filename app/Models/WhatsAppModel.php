<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
        'phone',
        'file_type',
        'file_name',
        'file_path',
        'file_size',
        'user_code'
        ];

    protected $table = 'whatsapp_media';
}
