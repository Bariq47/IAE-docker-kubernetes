<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class mahasiswaService extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'mahasiswa_service';
    protected $fillable = [
        'nim',
        'nama',
        'umur',
        'email',
        'alamat',
        'nomor',

    ];
}
