<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialDetails extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',           // Customer Name
        'account_number',  // SAXX0X0000XXX000XXXXX000
        'country',         // SA
        'bank',            // bank name
        'bank_branch',     // (optional)
        'email',           // email@domain.com
        'mobile_number',   // +966XXXXXXXXX
        'address_1',       // Riyadh
        'address_2',       // (optional)
        'shop_id',         // foreign key reference to the Shop model
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

}
