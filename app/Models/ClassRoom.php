<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'class_rooms';

    protected $fillable = ['nama_kelas'];

    // Relasi ke model Student (one to many)
    public function students()
    {
        return $this->hasMany(Student::class, 'kelas_id'); // Menghubungkan kelas_id
    }
}
