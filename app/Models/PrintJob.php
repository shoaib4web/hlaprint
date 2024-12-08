<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
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
        'status',
        'printNodeID',
        'transaction_id',
        'total_pages',
        'page_size',
        'page_orientation',
        'type',
        'shop_id',
        'copies'
    ];

    protected $table = 'print_jobs';

    public function printshop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'print_job_id', 'id')->withDefault();
    }

    public function shops()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

}
