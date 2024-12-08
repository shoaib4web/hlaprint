<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPrintRelation extends Model
{
    use HasFactory;
    protected $fillable = [
        'shops_id',
        'print_jobs_model_id'
    ];

    protected $table = 'job_shop_relation';
}
