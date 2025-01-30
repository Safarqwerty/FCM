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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nis')->unique();
            $table->unsignedBigInteger('kelas_id'); // Pastikan nama kolom adalah kelas_id
            $table->string('angkatan');
            $table->integer('pendapatan');
            $table->integer('tanggungan');
            $table->integer('tagihan_air');
            $table->integer('tagihan_listrik');
            $table->decimal('nilai_rapor', 5, 2);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->foreign('kelas_id')->references('id')->on('class_rooms'); // Relasi ke class_rooms
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
