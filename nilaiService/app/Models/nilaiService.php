<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nilaiService extends Model
{
    use HasFactory;
    protected $table = 'nilai_service';

    protected $fillable = [
        'nim',
        'kode_mk',
        'nilai',
    ];
}
