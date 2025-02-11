<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">{{ $student->exists ? 'Edit' : 'Tambah' }} Siswa</h3>

    <div class="mt-4">
        <div class="mt-4">
            <div class="w-full bg-white shadow-md rounded-md overflow-hidden border">
                <form
                    action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($student->exists)
                        @method('PUT')
                    @endif
                    <div class="p-6 bg-white rounded-md shadow-md">
                        <h2 class="text-lg text-gray-700 font-semibold capitalize">
                            {{ $student->exists ? 'Edit' : 'Masukkan' }} Data Siswa</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="text-gray-700" for="nama">Nama Lengkap</label>
                                <input name="nama" class="form-input w-full mt-2 rounded-md focus:border-indigo-600"
                                    type="text" value="{{ old('nama', $student->nama ?? '') }}" required>
                            </div>

                            <div>
                                <label class="text-gray-700" for="nis">Nomor Induk Siswa (NIS)</label>
                                <input name="nis" class="form-input w-full mt-2 rounded-md focus:border-indigo-600"
                                    type="text" value="{{ old('nis', $student->nis ?? '') }}" required>
                            </div>

                            <div>
                                <label class="text-gray-700" for="kelas">Kelas</label>
                                <select name="kelas_id"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('kelas_id', $student->kelas_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-gray-700" for="angkatan">Angkatan</label>
                                <input name="angkatan" class="form-input w-full mt-2 rounded-md focus:border-indigo-600"
                                    type="text" value="{{ old('angkatan', $student->angkatan ?? '') }}" required>
                            </div>

                            <div>
                                <label class="text-gray-700" for="pendapatan">Pendapatan Orang Tua</label>
                                <input name="pendapatan"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01" value="{{ old('pendapatan', $student->pendapatan ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="tanggungan">Tanggungan Orang Tua</label>
                                <input name="tanggungan"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    value="{{ old('tanggungan', $student->tanggungan ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="tagihan_air">Tagihan Air</label>
                                <input name="tagihan_air"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01" value="{{ old('tagihan_air', $student->tagihan_air ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="tagihan_listrik">Tagihan Listrik</label>
                                <input name="tagihan_listrik"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01"
                                    value="{{ old('tagihan_listrik', $student->tagihan_listrik ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="nilai_rapor">Rata - rata Nilai Rapor</label>
                                <input name="nilai_rapor"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01" value="{{ old('nilai_rapor', $student->nilai_rapor ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki"
                                        {{ old('jenis_kelamin', $student->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki - laki</option>
                                    <option value="Perempuan"
                                        {{ old('jenis_kelamin', $student->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:outline-none">
                                {{ $student->exists ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
