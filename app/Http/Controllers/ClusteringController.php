<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClusteringResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClusteringController extends Controller
{
    private $maxIterations = 100; // Maksimum iterasi
    private $epsilon = 0.00001;   // Nilai error untuk kondisi berhenti
    private $fuzziness = 2;       // Parameter fuzziness (biasanya 2)
    private $numberOfClusters = 2; // Jumlah cluster (C1 dan C2)

    /**
     * Menampilkan halaman clustering.
     */
    public function index()
    {
        $students = Student::all();
        return view('clustering.index', compact('students'));
    }

    /**
     * Menghitung clustering menggunakan metode Fuzzy C-Means (FCM).
     */
    public function calculate()
    {
        try {
            $students = Student::all();

            if ($students->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data siswa untuk diproses.');
            }

            $normalizedData = $this->normalizeData($students);
            $membershipMatrix = $this->initializeMembershipMatrix(count($normalizedData));

            $iteration = 0;
            $objectiveFunction = 0;
            $previousObjectiveFunction = 0;
            $iterationHistory = [];

            do {
                $previousObjectiveFunction = $objectiveFunction;
                $clusterCenters = $this->calculateClusterCenters($normalizedData, $membershipMatrix);
                $membershipMatrix = $this->updateMembershipMatrix($normalizedData, $clusterCenters);
                $objectiveFunction = $this->calculateObjectiveFunction($normalizedData, $clusterCenters, $membershipMatrix);

                $iterationHistory[] = [
                    'iteration' => $iteration,
                    'objective_function' => $objectiveFunction,
                    'difference' => abs($objectiveFunction - $previousObjectiveFunction)
                ];

                $iteration++;

            } while (abs($objectiveFunction - $previousObjectiveFunction) > $this->epsilon &&
                    $iteration < $this->maxIterations);

            $results = $this->determineClusterResults($students, $membershipMatrix);
            $stats = $this->calculateStatistics($results);

            // Simpan hasil clustering ke database
            foreach ($results as $result) {
                ClusteringResult::updateOrCreate(
                    ['student_id' => $result['student']->id], // Jika sudah ada, update
                    [
                        'cluster' => $result['cluster'],
                        'membership_values' => json_encode($result['membership_values']),
                        'confidence' => $result['confidence'],
                        'eligible' => $result['eligible']
                    ]
                );
            }

            Log::info('Clustering completed', [
                'iterations' => $iteration,
                'final_objective_function' => $objectiveFunction,
                'stats' => $stats
            ]);

            return view('admin.tables', [
                'results' => $results,
                'stats' => $stats,
                'iteration_count' => $iteration,
                'objective_function' => $objectiveFunction,
                'iteration_history' => $iterationHistory
            ]);

        } catch (\Exception $e) {
            Log::error('Clustering error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan dalam proses clustering.');
        }
    }

    /**
     * Normalisasi data siswa.
     */
    private function normalizeData($students)
    {
        $normalizedData = [];
        $weights = [
            'tanggungan' => 0.2,
            'pendapatan' => 0.3,
            'tagihan_air' => 0.15,
            'tagihan_listrik' => 0.15,
            'nilai_rapor' => 0.2
        ];

        foreach ($students as $student) {
            $normalized = [
                $this->normalizeDependents($student->tanggungan) * $weights['tanggungan'],
                $this->normalizeIncome($student->pendapatan) * $weights['pendapatan'],
                $this->normalizeWaterBill($student->tagihan_air) * $weights['tagihan_air'],
                $this->normalizeElectricBill($student->tagihan_listrik) * $weights['tagihan_listrik'],
                $this->normalizeGrades($student->nilai_rapor) * $weights['nilai_rapor']
            ];

            $normalizedData[] = $normalized;
        }

        return $normalizedData;
    }

    /**
     * Normalisasi jumlah tanggungan.
     */
    private function normalizeDependents($value)
    {
        if ($value == 1) return 0;
        if ($value == 2) return 0.25;
        if ($value == 3) return 0.5;
        if ($value == 4) return 0.75;
        if ($value > 4) return 1;
    }

    /**
     * Normalisasi penghasilan orang tua.
     */
    private function normalizeIncome($value)
    {
        if ($value >= 5000000) return 0;
        if ($value >= 3000000 && $value < 5000000) return 0.25;
        if ($value >= 1500000 && $value < 3000000) return 0.5;
        if ($value >= 1000000 && $value < 1500000) return 0.75;
        if ($value < 1000000) return 1;
    }

    /**
     * Normalisasi tagihan air.
     */
    private function normalizeWaterBill($value)
    {
        if ($value >= 300000) return 1;
        if ($value >= 200000 && $value < 300000) return 0.75;
        if ($value >= 100000 && $value < 200000) return 0.5;
        if ($value >= 50000 && $value < 100000) return 0.25;
        if ($value < 50000) return 0;
    }

    /**
     * Normalisasi tagihan listrik.
     */
    private function normalizeElectricBill($value)
    {
        if ($value >= 300000) return 1;
        if ($value >= 200000 && $value < 300000) return 0.75;
        if ($value >= 100000 && $value < 200000) return 0.5;
        if ($value >= 50000 && $value < 100000) return 0.25;
        if ($value < 50000) return 0;
    }

    /**
     * Normalisasi nilai rapor.
     */
    private function normalizeGrades($value)
    {
        if ($value >= 90 && $value <= 100) return 1;
        if ($value >= 80 && $value < 90) return 0.75;
        if ($value >= 70 && $value < 80) return 0.5;
        if ($value >= 60 && $value < 70) return 0.25;
        if ($value < 60) return 0;
    }

    /**
     * Inisialisasi matriks keanggotaan.
     */
    private function initializeMembershipMatrix($dataCount)
    {
        $matrix = [];

        for ($i = 0; $i < $dataCount; $i++) {
            $row = [];
            $sum = 0;

            for ($j = 0; $j < $this->numberOfClusters; $j++) {
                $row[$j] = mt_rand(10, 90) / 100;
                $sum += $row[$j];
            }

            for ($j = 0; $j < $this->numberOfClusters; $j++) {
                $row[$j] /= $sum;
            }

            $matrix[] = $row;
        }

        return $matrix;
    }

    /**
     * Hitung pusat cluster.
     */
    private function calculateClusterCenters($data, $membershipMatrix)
    {
        $centers = [];

        for ($j = 0; $j < $this->numberOfClusters; $j++) {
            $numerator = array_fill(0, count($data[0]), 0);
            $denominator = 0;

            for ($i = 0; $i < count($data); $i++) {
                $membershipValue = pow($membershipMatrix[$i][$j], $this->fuzziness);

                for ($k = 0; $k < count($data[$i]); $k++) {
                    $numerator[$k] += $membershipValue * $data[$i][$k];
                }

                $denominator += $membershipValue;
            }

            $denominator = max($denominator, 1e-10);

            $centers[$j] = array_map(function($value) use ($denominator) {
                return $value / $denominator;
            }, $numerator);
        }

        return $centers;
    }

    /**
     * Perbarui matriks keanggotaan.
     */
    private function updateMembershipMatrix($data, $centers)
    {
        $matrix = [];

        for ($i = 0; $i < count($data); $i++) {
            $matrix[$i] = [];
            $denominators = [];

            // Hitung semua jarak terlebih dahulu
            for ($k = 0; $k < $this->numberOfClusters; $k++) {
                $distance = $this->calculateDistance($data[$i], $centers[$k]);
                if ($distance == 0) {
                    // Jika titik tepat di pusat, berikan keanggotaan penuh ke cluster ini
                    $matrix[$i] = array_fill(0, $this->numberOfClusters, 0);
                    $matrix[$i][$k] = 1;
                    continue 2;
                }
                $denominators[$k] = pow($distance, 2 / ($this->fuzziness - 1));
            }

            // Hitung nilai keanggotaan
            $denominator_sum = array_sum($denominators);
            for ($j = 0; $j < $this->numberOfClusters; $j++) {
                $matrix[$i][$j] = $denominators[$j] / $denominator_sum;
            }
        }

        return $matrix;
    }

    /**
     * Hitung jarak Euclidean.
     */
    private function calculateDistance($point1, $point2)
    {
        $sum = 0;
        for ($i = 0; $i < count($point1); $i++) {
            $diff = $point1[$i] - $point2[$i];
            $sum += $diff * $diff;
        }
        return sqrt($sum) + 1e-10;  // Tambahkan epsilon kecil untuk menghindari pembagian oleh nol
    }

    /**
     * Hitung fungsi objektif.
     */
    private function calculateObjectiveFunction($data, $centers, $membershipMatrix)
    {
        $sum = 0;

        for ($i = 0; $i < count($data); $i++) {
            for ($j = 0; $j < $this->numberOfClusters; $j++) {
                $distance = $this->calculateDistance($data[$i], $centers[$j]);
                $sum += pow($membershipMatrix[$i][$j], $this->fuzziness) * pow($distance, 2);
            }
        }

        return $sum;
    }

    /**
     * Tentukan hasil clustering.
     */
    private function determineClusterResults($students, $membershipMatrix)
    {
        $results = [];

        foreach ($students as $index => $student) {
            // Get membership values for current student
            $memberships = $membershipMatrix[$index];

            // Find highest membership value and its corresponding cluster
            $maxMembership = max($memberships);
            $cluster = array_search($maxMembership, $memberships);

            // Calculate confidence score
            $membershipsCopy = $memberships;
            arsort($membershipsCopy);
            $values = array_values($membershipsCopy);
            $confidence = count($values) > 1 ? $values[0] - $values[1] : 1;

            $results[] = [
                'student' => $student, // Simpan object student langsung, bukan sebagai array
                'cluster' => $cluster,
                'membership_values' => $memberships,
                'confidence' => round($confidence, 4),
                'eligible' => $cluster == 0  // Cluster 0 dianggap sebagai kelompok layak
            ];
        }

        return $results;
    }

    /**
     * Hitung statistik clustering.
     */
    private function calculateStatistics($results)
    {
        $eligible = 0;
        $notEligible = 0;
        $totalConfidence = 0;

        foreach ($results as $result) {
            if ($result['eligible']) {
                $eligible++;
            } else {
                $notEligible++;
            }
            $totalConfidence += $result['confidence'];
        }

        $totalStudents = count($results);

        return [
            'total_students' => $totalStudents,
            'eligible_count' => $eligible,
            'not_eligible_count' => $notEligible,
            'eligible_percentage' => round(($eligible / $totalStudents) * 100, 2),
            'not_eligible_percentage' => round(($notEligible / $totalStudents) * 100, 2),
            'average_confidence' => round($totalConfidence / $totalStudents, 4)
        ];
    }
}
