<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function ($shop) {
            $shop->shopOption()->create([
                'subscription_status' => true, // default value
                'subscription_start' => now(),  // default value
                'subscription_renew' => now()->addYear(), // default value: add 1 year
                'payment_due' => now()->addMonths(4), // default value: add 4 months
                'is_installment' => false, // default value
                'print_invoice' => true, // default value
                'print_separator' => true, // default value
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id')->withDefault();
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    public function printjobs()
    {
        return $this->hasMany(PrintJob::class, 'shop_id', 'id');
    }

    public function jobs()
    {
        return $this->hasMany(PrintJob::class, 'shop_id', 'id');
    }

    public function shopOption()
    {
        return $this->hasOne(ShopOption::class, 'shop_id', 'id');
    }

    public function financialDetails()
    {
        return $this->hasOne(FinancialDetails::class);
    }
}
