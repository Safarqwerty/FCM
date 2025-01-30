<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassRoomController extends Controller
{
    /**
     * Tampilkan daftar kelas dan form.
     */
    public function index(Request $request)
    {
        try {
            $class = ClassRoom::latest()->get();
            $editClass = $request->query('edit') ? ClassRoom::find($request->query('edit')) : null;

            return view('admin.class', compact('class', 'editClass'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengambil data kelas.');
        }
    }

    /**
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_kelas' => 'required|string|unique:class_rooms,nama_kelas|max:50|regex:/^[a-zA-Z0-9\s-]+$/',
            ], [
                'nama_kelas.required' => 'Nama kelas harus diisi.',
                'nama_kelas.unique' => 'Nama kelas sudah digunakan.',
                'nama_kelas.max' => 'Nama kelas maksimal 50 karakter.',
                'nama_kelas.regex' => 'Nama kelas hanya boleh berisi huruf, angka, spasi, dan tanda hubung.',
            ]);

            DB::beginTransaction();

            ClassRoom::create($validated);

            DB::commit();

            return redirect()->route('admin.class')
                ->with('success', 'Kelas berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan kelas.')
                ->withInput();
        }
    }

    /**
     * Update kelas yang ada.
     */
    public function update(Request $request, ClassRoom $classRoom)
    {
        try {
            $validated = $request->validate([
                'nama_kelas' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s-]+$/|unique:class_rooms,nama_kelas,' . $classRoom->id,
            ], [
                'nama_kelas.required' => 'Nama kelas harus diisi.',
                'nama_kelas.unique' => 'Nama kelas sudah digunakan.',
                'nama_kelas.max' => 'Nama kelas maksimal 50 karakter.',
                'nama_kelas.regex' => 'Nama kelas hanya boleh berisi huruf, angka, spasi, dan tanda hubung.',
            ]);

            DB::beginTransaction();

            $classRoom->update($validated);

            DB::commit();

            return redirect()->route('admin.class')
                ->with('success', 'Kelas berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui kelas.')
                ->withInput();
        }
    }

    /**
     * Hapus kelas.
     */
    public function destroy(ClassRoom $classRoom)
    {
        try {
            DB::beginTransaction();

            $classRoom->delete();

            DB::commit();

            return redirect()->route('admin.class')
                ->with('success', 'Kelas berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus kelas.');
        }
    }
}
