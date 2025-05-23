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
        // Tambahkan logging untuk debugging
        Log::info('Accessing clustering index page');

        // Ambil hasil clustering terakhir dari database
        $lastResults = $this->getLastResults();

        Log::info('Last results retrieved', ['hasResults' => !is_null($lastResults)]);

        if ($lastResults) {
            return view('admin.tables', [
                'results' => $lastResults['results'],
                'stats' => $lastResults['stats'],
                'iteration_count' => $lastResults['iteration_count'],
                'objective_function' => $lastResults['objective_function'],
                'iteration_history' => $lastResults['iteration_history']
            ]);
        }

        // Jika belum ada hasil, tampilkan halaman kosong
        Log::info('No clustering results found, showing empty view');

        return view('admin.tables', [
            'results' => [],
            'stats' => null,
            'iteration_count' => 0,
            'objective_function' => 0,
            'iteration_history' => []
        ]);
    }

    private function getLastResults()
    {
        try {
            Log::info('Fetching clustering results from database');

            $clusteringResults = ClusteringResult::with(['student', 'student.classRoom'])->get();

            Log::info('Retrieved clustering results', ['count' => $clusteringResults->count()]);

            if ($clusteringResults->isEmpty()) {
                Log::info('No clustering results found in database');
                return null;
            }

            $results = [];
            foreach ($clusteringResults as $result) {
                // Pastikan relasi student ada
                if (!$result->student) {
                    Log::warning('Student relation not found for clustering result', ['id' => $result->id]);
                    continue;
                }

                // Pastikan relasi classRoom ada
                if (!$result->student->classRoom) {
                    Log::warning('ClassRoom relation not found for student', ['student_id' => $result->student->id]);
                    continue;
                }

                $results[] = [
                    'student' => $result->student,
                    'cluster' => $result->cluster,
                    'membership_values' => json_decode($result->membership_values),
                    'confidence' => $result->confidence,
                    'eligible' => $result->eligible
                ];
            }

            if (empty($results)) {
                Log::warning('No valid results after processing relationship checks');
                return null;
            }

            $stats = $this->calculateStatistics($results);

            return [
                'results' => $results,
                'stats' => $stats,
                'iteration_count' => session('last_iteration_count', 0),
                'objective_function' => session('last_objective_function', 0),
                'iteration_history' => session('iteration_history', [])
            ];
        } catch (\Exception $e) {
            Log::error('Error in getLastResults: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Menghitung clustering menggunakan metode Fuzzy C-Means (FCM).
     */
    public function calculate()
    {
        try {
            Log::info('Starting clustering calculation');

            // Hapus hasil clustering sebelumnya
            ClusteringResult::truncate();
            Log::info('Previous clustering results truncated');

            $students = Student::with('classRoom')->get();
            Log::info('Retrieved students for clustering', ['count' => $students->count()]);

            if ($students->isEmpty()) {
                Log::warning('No students data available for clustering');
                return redirect()->route('admin.tables')->with('error', 'Tidak ada data siswa untuk diproses.');
            }

            $normalizedData = $this->normalizeData($students);
            $membershipMatrix = $this->initializeMembershipMatrix(count($normalizedData));

            $iteration = 0;
            $objectiveFunction = 0;
            $previousObjectiveFunction = 0;
            $iterationHistory = [];

            Log::info('Starting FCM iterations');

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

                Log::debug('FCM iteration completed', [
                    'iteration' => $iteration,
                    'objective_function' => $objectiveFunction,
                    'difference' => abs($objectiveFunction - $previousObjectiveFunction)
                ]);

            } while (abs($objectiveFunction - $previousObjectiveFunction) > $this->epsilon &&
                    $iteration < $this->maxIterations);

            Log::info('FCM iterations completed', ['total_iterations' => $iteration]);

            $results = $this->determineClusterResults($students, $membershipMatrix);
            $stats = $this->calculateStatistics($results);

            // Simpan hasil ke database
            Log::info('Saving clustering results to database');

            foreach ($results as $result) {
                ClusteringResult::create([
                    'student_id' => $result['student']->id,
                    'cluster' => $result['cluster'],
                    'membership_values' => json_encode($result['membership_values']),
                    'confidence' => $result['confidence'],
                    'eligible' => $result['eligible']
                ]);
            }

            Log::info('Successfully saved clustering results', ['count' => count($results)]);

            // Simpan informasi iterasi ke session
            session([
                'last_iteration_count' => $iteration,
                'last_objective_function' => $objectiveFunction,
                'iteration_history' => $iterationHistory
            ]);

            Log::info('Clustering completed successfully', [
                'iterations' => $iteration,
                'final_objective_function' => $objectiveFunction,
                'total_results' => count($results)
            ]);

            return redirect()->route('admin.tables')->with('success', 'Hasil clustering telah diperbarui.');

        } catch (\Exception $e) {
            Log::error('Clustering error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.tables')->with('error', 'Terjadi kesalahan dalam proses clustering: ' . $e->getMessage());
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
            // Gunakan metode yang lebih deterministik untuk inisialisasi
            $row[0] = 0.5 + (mt_rand(-20, 20) / 100); // Nilai acak antara 0.3 dan 0.7
            $row[1] = 1 - $row[0]; // Memastikan jumlah keanggotaan = 1

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

            for ($j = 0; $j < $this->numberOfClusters; $j++) {
                $distanceToCurrentCenter = $this->calculateDistance($data[$i], $centers[$j]);

                if ($distanceToCurrentCenter == 0) {
                    // Jika data point tepat berada di pusat cluster j
                    $row = array_fill(0, $this->numberOfClusters, 0);
                    $row[$j] = 1;
                    $matrix[$i] = $row;
                    continue 2;
                }

                $sum = 0.0;
                for ($k = 0; $k < $this->numberOfClusters; $k++) {
                    $distanceToOtherCenter = $this->calculateDistance($data[$i], $centers[$k]);
                    $distanceToOtherCenter = max($distanceToOtherCenter, 1e-10); // Hindari pembagian dengan nol

                    $ratio = $distanceToCurrentCenter / $distanceToOtherCenter;
                    $sum += pow($ratio, 2 / ($this->fuzziness - 1));
                }

                $matrix[$i][$j] = 1 / $sum;
            }
        }

        return $matrix;
    }

    /**
     * Hitung jarak Euclidean.
     */
    private function calculateDistance($a, $b)
    {
        $sum = 0;
        for ($i = 0; $i < count($a); $i++) {
            $sum += pow($a[$i] - $b[$i], 2);
        }
        return sqrt($sum);
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
        $centers = $this->calculateClusterCenters($this->normalizeData($students), $membershipMatrix);

        // Tentukan cluster mana yang merepresentasikan "layak" berdasarkan karakteristik pusat cluster
        $eligibleCluster = $this->determineEligibleCluster($centers);

        foreach ($students as $index => $student) {
            $memberships = $membershipMatrix[$index];
            $maxMembership = max($memberships);
            $cluster = array_search($maxMembership, $memberships);

            $membershipsCopy = $memberships;
            arsort($membershipsCopy);
            $values = array_values($membershipsCopy);
            $confidence = count($values) > 1 ? $values[0] - $values[1] : 1;

            $results[] = [
                'student' => $student,
                'cluster' => $cluster,
                'membership_values' => $memberships,
                'confidence' => round($confidence, 4),
                'eligible' => $cluster == $eligibleCluster
            ];
        }

        return $results;
    }

    private function determineEligibleCluster($centers)
    {
        // Karena nilai yang lebih tinggi dalam kriteria normalisasi mengindikasikan kebutuhan lebih tinggi
        // (misalnya: pendapatan rendah = nilai normalisasi tinggi), maka cluster dengan nilai rata-rata
        // lebih tinggi kemungkinan adalah cluster "layak"
        $clusterScores = [];

        for ($i = 0; $i < $this->numberOfClusters; $i++) {
            $clusterScores[$i] = array_sum($centers[$i]) / count($centers[$i]);
        }

        // Cluster dengan nilai rata-rata tertinggi dianggap sebagai "layak"
        return array_search(max($clusterScores), $clusterScores);
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

        if ($totalStudents == 0) {
            return [
                'total_students' => 0,
                'eligible_count' => 0,
                'not_eligible_count' => 0,
                'eligible_percentage' => 0,
                'not_eligible_percentage' => 0,
                'average_confidence' => 0
            ];
        }

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
