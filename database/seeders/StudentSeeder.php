<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Inisialisasi Faker
        $faker = Faker::create('id');

        // Array untuk nilai random angkatan
        $angkatan = ['2021', '2022', '2023', '2024'];

        // Membuat 100 data dummy
        for ($i = 0; $i < 100; $i++) {
            DB::table('students')->insert([
                'nama' => $faker->name(),
                'nis' => $faker->unique()->numberBetween(10000, 99999),
                'kelas_id' => $faker->numberBetween(1, 2),
                'angkatan' => $faker->randomElement($angkatan),
                'pendapatan' => $faker->numberBetween(4000000,5000000),
                'tanggungan' => $faker->numberBetween(3, 6),
                'tagihan_air' => $faker->numberBetween(100000, 1000000),
                'tagihan_listrik' => $faker->numberBetween(200000, 1000000),
                'nilai_rapor' => $faker->randomFloat(2, 40, 98),
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
