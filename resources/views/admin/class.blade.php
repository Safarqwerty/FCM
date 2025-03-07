<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Tambah Kelas</h3>

    <div class="mt-4">
        <div class="max-w-7xl mx-auto">
            <!-- Form Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST"
                        action="{{ isset($editClass) ? route('admin.class.update', $editClass->id) : route('admin.class.store') }}"
                        class="space-y-4">
                        @csrf
                        @if (isset($editClass))
                            @method('PUT')
                        @endif

                        <!-- Input Hidden untuk ID -->
                        <input type="hidden" name="id" value="{{ isset($editClass) ? $editClass->id : '' }}">

                        <div>
                            <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" placeholder="Masukkan nama kelas"
                                value="{{ isset($editClass) ? $editClass->nama_kelas : old('nama_kelas') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                            @error('nama_kelas')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            @if (isset($editClass))
                                <a href="{{ route('admin.class') }}"
                                    class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Batal
                                </a>
                            @endif
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ isset($editClass) ? 'Update Kelas' : 'Tambah Kelas' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Kelas
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($class as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->nama_kelas }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-4">
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.class', ['edit' => $item->id]) }}"
                                                class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                Edit
                                            </a>

                                            <!-- Delete Form -->
                                            <form action="{{ route('admin.class.destroy', $item->id) }}" method="POST"
                                                class="flex items-center">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
