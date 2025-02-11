<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusteringResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'cluster',
        'membership_values',
        'confidence',
        'eligible',
    ];

    protected $casts = [
        'membership_values' => 'array', // Ubah JSON ke array otomatis
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
