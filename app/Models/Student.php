<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'nama',
        'nis',
        'kelas_id',  // Menggunakan kelas_id sebagai penghubung ke tabel class_rooms
        'angkatan',
        'pendapatan',
        'tanggungan',
        'tagihan_air',
        'tagihan_listrik',
        'nilai_rapor',
        'jenis_kelamin',
    ];

    // Relasi ke model ClassRoom
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'kelas_id'); // Kelas_id adalah foreign key yang benar
    }
}
