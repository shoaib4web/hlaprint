<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'amount',
        'trans_id',
        'monthly_id',
        'shop_id',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'trans_id', 'id')->withDefault();
    }
}
