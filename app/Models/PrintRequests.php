<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintRequests extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'file',
        'status',
    ];
    protected $table = 'print_requests';
}
