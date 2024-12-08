<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJobsModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'filename',
        'code',
        'color',
        'double_sided',
        'pages_start',
        'page_end',
        'status'
        ];

    protected $table = 'print_jobs';
}
