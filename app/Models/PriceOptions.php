<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceOptions extends Model
{
    use HasFactory; 

    protected $fillable = [
        'page_size',
        'color_type',
        'sidedness',
        'no_of_pages',
        'shop_id',
        'base_price'
    ];
    protected $table = 'price_options';
    
    
    
    
}