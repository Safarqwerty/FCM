<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $students = Student::with('classRoom')->paginate(10);
        return view('admin.dashboard', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $class = ClassRoom::all(); // Ambil data kelas dari database
        return view('admin.forms', compact('class')); // Kirim data kelas ke view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nis' => 'required|string|max:20|unique:students',
                'kelas_id' => 'required|exists:class_rooms,id',
                'angkatan' => 'required|digits:4',
                'pendapatan' => 'required|integer',
                'tanggungan' => 'required|integer',
                'tagihan_air' => 'required|integer',
                'tagihan_listrik' => 'required|integer',
                'nilai_rapor' => 'required|numeric|between:0,100',
                'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            ]);

            // Menyimpan data siswa yang telah divalidasi
            $student = Student::create($validated);

            if (!$student) {
                throw new \Exception('Failed to create student record');
            }

            return redirect()->route('admin.dashboard')->with('success', 'Data siswa berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $class = ClassRoom::all(); // Ambil data kelas untuk form edit
        return view('admin.edit-student', compact('student', 'class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:students,nis,' . $student->id,
            'kelas_id' => 'required|exists:class_rooms,id', // Menggunakan kelas_id
            'angkatan' => 'required|digits:4',
            'pendapatan' => 'required|integer',
            'tanggungan' => 'required|integer',
            'tagihan_air' => 'required|integer',
            'tagihan_listrik' => 'required|integer',
            'nilai_rapor' => 'required|numeric|between:0,100',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
        ]);

        // Update data siswa
        $student->update([
            'nama' => $validated['nama'],
            'nis' => $validated['nis'],
            'kelas_id' => $validated['kelas_id'], // Menggunakan kelas_id
            'angkatan' => $validated['angkatan'],
            'pendapatan' => $validated['pendapatan'],
            'tanggungan' => $validated['tanggungan'],
            'tagihan_air' => $validated['tagihan_air'],
            'tagihan_listrik' => $validated['tagihan_listrik'],
            'nilai_rapor' => $validated['nilai_rapor'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Data siswa berhasil dihapus.');
    }
}
