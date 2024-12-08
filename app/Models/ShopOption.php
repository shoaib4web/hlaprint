<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'subscription_status',
        'subscription_start',
        'subscription_renew',
        'payment_due',
        'is_installment',
        'print_invoice',
        'print_separator',
    ];

    /**
     * Get the shop that owns the option.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
