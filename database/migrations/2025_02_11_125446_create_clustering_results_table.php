<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clustering_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->integer('cluster');
            $table->json('membership_values'); // Simpan dalam format JSON
            $table->decimal('confidence', 5, 4);
            $table->boolean('eligible'); // Menyatakan apakah siswa layak atau tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clustering_results');
    }
};

