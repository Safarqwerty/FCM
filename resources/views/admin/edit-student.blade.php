<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">{{ $student->exists ? 'Edit' : 'Tambah' }} Siswa</h3>

    <div class="mt-4">
        <div class="w-full bg-white shadow-md rounded-md overflow-hidden border">
            <form
                action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($student->exists)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                    <!-- Bagian Kiri: Data Siswa -->
                    <div class="bg-white rounded-md shadow-md p-6">
                        <h2 class="text-lg text-gray-700 font-semibold capitalize mb-4">
                            {{ $student->exists ? 'Edit' : 'Masukkan' }} Data Siswa
                        </h2>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-gray-700" for="nama">Nama Lengkap</label>
                                <input name="nama" placeholder="Masukkan nama lengkap"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="text"
                                    value="{{ old('nama', $student->nama ?? '') }}" required>
                            </div>

                            <div>
                                <label class="text-gray-700" for="nis">Nomor Induk Siswa (NIS)</label>
                                <input name="nis" placeholder="Masukkan NIS"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="text"
                                    value="{{ old('nis', $student->nis ?? '') }}" required>
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
                                <label class="text-gray-700" for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki"
                                        {{ old('jenis_kelamin', $student->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki - laki
                                    </option>
                                    <option value="Perempuan"
                                        {{ old('jenis_kelamin', $student->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="text-gray-700" for="angkatan">Angkatan</label>
                                <select name="angkatan"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" required>
                                    <option value="">-- Pilih Tahun Angkatan --</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 5;
                                        $endYear = $currentYear + 1;
                                    @endphp
                                    @for ($year = $startYear; $year <= $endYear; $year++)
                                        <option value="{{ $year }}"
                                            {{ old('angkatan', $student->angkatan ?? '') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Kanan: Data Kriteria -->
                    <div class="bg-white rounded-md shadow-md p-6">
                        <h2 class="text-lg text-gray-700 font-semibold capitalize mb-4">Data Kriteria</h2>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-gray-700" for="pendapatan">Pendapatan Orang Tua</label>
                                <input name="pendapatan" placeholder="Masukkan pendapatan orang tua"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01" value="{{ old('pendapatan', $student->pendapatan ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="tanggungan">Tanggungan Orang Tua</label>
                                <input name="tanggungan" placeholder="Jumlah tanggungan keluarga"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    value="{{ old('tanggungan', $student->tanggungan ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="tagihan_air">Tagihan Air</label>
                                <input name="tagihan_air" placeholder="Masukkan nominal tagihan air"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01" value="{{ old('tagihan_air', $student->tagihan_air ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="tagihan_listrik">Tagihan Listrik</label>
                                <input name="tagihan_listrik" placeholder="Masukkan nominal tagihan listrik"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01"
                                    value="{{ old('tagihan_listrik', $student->tagihan_listrik ?? '') }}">
                            </div>

                            <div>
                                <label class="text-gray-700" for="nilai_rapor">Rata - rata Nilai Rapor</label>
                                <input name="nilai_rapor" placeholder="Masukkan rata-rata nilai rapor"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01" value="{{ old('nilai_rapor', $student->nilai_rapor ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 mb-6 flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:outline-none">
                        {{ $student->exists ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
