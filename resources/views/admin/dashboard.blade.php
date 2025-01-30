<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Dashboard</h3>

    <div class="mt-4">
        <div class="flex flex-wrap -mx-6">
            <!-- Card Siswa -->
            <div class="w-full px-6 sm:w-1/2 xl:w-1/3">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
                    <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor"
                                d="M16 14c3.314 0 6 2.686 6 6v2h-2v-2c0-2.206-1.794-4-4-4s-4 1.794-4 4v2h-2v-2c0-3.314 2.686-6 6-6zM8 14c3.314 0 6 2.686 6 6v2H2v-2c0-3.314 2.686-6 6-6zm0-2C6.343 12 5 10.657 5 9s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm8 0c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z" />
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">8,282</h4>
                        <div class="text-gray-500">Siswa</div>
                    </div>
                </div>
            </div>

            <!-- Card Kelas -->
            <div class="w-full mt-6 px-6 sm:w-1/2 xl:w-1/3 sm:mt-0">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
                    <div class="p-3 rounded-full bg-orange-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor"
                                d="M21 18V5c0-1.104-.896-2-2-2H5C3.896 3 3 3.896 3 5v13H0v2h24v-2h-3zM5 5h14v13H5V5zm4 10h2v-2H9v2zm4 0h2v-2h-2v2z" />
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">200,521</h4>
                        <div class="text-gray-500">Kelas</div>
                    </div>
                </div>
            </div>

            <!-- Card Admin -->
            <div class="w-full mt-6 px-6 sm:w-1/2 xl:w-1/3 xl:mt-0">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
                    <div class="p-3 rounded-full bg-pink-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor"
                                d="M12 12c2.206 0 4-1.794 4-4V5c0-2.206-1.794-4-4-4S8 2.794 8 5v3c0 2.206 1.794 4 4 4zm8 10v-2c0-3.314-2.686-6-6-6H10c-3.314 0-6 2.686-6 6v2h16zm2 0h-2v-2c0-3.896-2.579-7.2-6.18-8.315.58-.445 1.16-1.025 1.579-1.725C18.081 10.617 21 14.364 21 19v2h1z" />
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">215,542</h4>
                        <div class="text-gray-500">Admin</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col mt-8">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th
                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th
                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas
                            </th>
                            <th
                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis Kelamin
                            </th>
                            <th
                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendapatan
                            </th>
                            <th
                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @if ($students->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Data masih kosong.
                                </td>
                            </tr>
                        @else
                            @foreach ($students as $student)
                                <tr>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-500">
                                        {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->nama }}
                                                </div>
                                                <div class="text-sm text-gray-500">NIS: {{ $student->nis }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm text-gray-900">{{ $student->classRoom->nama_kelas }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->angkatan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <span
                                            class="px-2 inline-flex text-xs font-semibold rounded-full {{ $student->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $student->jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-500">
                                        Rp {{ number_format($student->pendapatan, 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.students.edit', ['student' => $student->id]) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 focus:outline-none">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="px-6 py-4">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
