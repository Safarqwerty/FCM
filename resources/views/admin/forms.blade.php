<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Tambah Siswa</h3>

    <div class="mt-4">
        <div class="mt-4">
            <div class="w-full bg-white shadow-md rounded-md overflow-hidden border">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 bg-white rounded-md shadow-md">
                        <h2 class="text-lg text-gray-700 font-semibold capitalize">Masukka Data Siswa</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="text-gray-700" for="nama">Nama Lengkap</label>
                                <input name="nama" class="form-input w-full mt-2 rounded-md focus:border-indigo-600"
                                    type="text" required>
                            </div>

                            <div>
                                <label class="text-gray-700" for="nis">Nomor Induk Siswa (NIS)</label>
                                <input name="nis" class="form-input w-full mt-2 rounded-md focus:border-indigo-600"
                                    type="text" required>
                            </div>

                            <div>
                                <label class="text-gray-700" for="kelas">Kelas</label>
                                <select name="kelas_id"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-gray-700" for="pendapatan">Pendapatan Orang Tua</label>
                                <input name="pendapatan"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01">
                            </div>

                            <div>
                                <label class="text-gray-700" for="tanggungan">Tanggungan Orang Tua</label>
                                <input name="tanggungan"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number">
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
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="text-gray-700" for="tagihan_air">Tagihan Air</label>
                                <input name="tagihan_air"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01">
                            </div>

                            <div>
                                <label class="text-gray-700" for="tagihan_listrik">Tagihan Listrik</label>
                                <input name="tagihan_listrik"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01">
                            </div>

                            <div>
                                <label class="text-gray-700" for="nilai_rapor">Rata - rata Nilai Rapor</label>
                                <input name="nilai_rapor"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="number"
                                    step="0.01">
                            </div>

                            <div>
                                <label class="text-gray-700" for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="form-input w-full mt-2 rounded-md focus:border-indigo-600">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki">Laki - laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:outline-none">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
