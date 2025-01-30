<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clustering_results', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Relasi ke tabel students
            $table->string('nama'); // Nama siswa
            $table->string('nis'); // NIS siswa
            $table->float('weight_c1'); // Bobot cluster C1
            $table->float('weight_c2'); // Bobot cluster C2
            $table->string('cluster'); // Kategori cluster: "Layak" atau "Tidak Layak"
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clustering_results');
    }
};
