<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'amount',
        'currency',
        'type',
        'print_job_id',
        'invoice_id',
    ];

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'trans_id', 'id')->withDefault();
    }

    public function printjob()
    {
        return $this->belongsTo(PrintJob::class, 'print_job_id', 'id')->withDefault();
    }
}
