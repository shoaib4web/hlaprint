<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfFilesModel extends Model
{
    use HasFactory; 

    protected $fillable = [
        'phone',
        'fileName',
        'fileLocation',
        'status'
    ];
    protected $table = 'pdf_files';
}
