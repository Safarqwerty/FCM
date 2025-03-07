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
                        <h4 class="text-2xl font-semibold text-gray-700">{{ number_format($stats['student_count']) }}
                        </h4>
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
                        <h4 class="text-2xl font-semibold text-gray-700">{{ number_format($stats['class_count']) }}</h4>
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
                        <h4 class="text-2xl font-semibold text-gray-700">{{ number_format($stats['admin_count']) }}</h4>
                        <div class="text-gray-500">Admin</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        @php
            $colorClasses = match (session('type')) {
                'create' => 'bg-green-100 border-green-400 text-green-700',
                'update' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
                'delete' => 'bg-red-100 border-red-400 text-red-700',
                default => 'bg-blue-100 border-blue-400 text-blue-700',
            };
        @endphp

        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show" x-transition
            class="mt-4 {{ $colorClasses }} px-4 py-3 rounded relative mb-4 border" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="space-y-4">
            <div class="flex flex-wrap -mx-2">
                <!-- Search Field -->
                <div class="px-2 w-full md:w-1/3 mb-4 md:mb-0">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" id="search" placeholder="Cari nama atau NIS..."
                        value="{{ request('search') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Class Filter -->
                <div class="px-2 w-full md:w-1/5 mb-4 md:mb-0">
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="class_id" id="class_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua Kelas</option>
                        @foreach ($classRooms as $class)
                            <option value="{{ $class->id }}"
                                {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender Filter -->
                <div class="px-2 w-full md:w-1/5 mb-4 md:mb-0">
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="gender" id="gender"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua</option>
                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                </div>

                <!-- Batch Year Filter -->
                <div class="px-2 w-full md:w-1/5 mb-4 md:mb-0">
                    <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-1">Angkatan</label>
                    <select name="angkatan" id="angkatan"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Semua Angkatan</option>
                        @foreach ($batchYears as $year)
                            <option value="{{ $year }}" {{ request('angkatan') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="px-2 w-full md:w-1/6 flex items-end">
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filter
                    </button>
                </div>
            </div>

            <!-- Reset Filters Link -->
            <div class="text-right">
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Reset
                    Filter</a>
            </div>
        </form>
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
                                NIS
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
                                Angkatan
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
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
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
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-900">
                                        {{ $student->nama }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-500">
                                        {{ $student->nis }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-900">
                                        {{ $student->classRoom->nama_kelas }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <span
                                            class="px-2 inline-flex text-xs font-semibold rounded-full {{ $student->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $student->jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-500">
                                        {{ $student->angkatan }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-medium">
                                        <a href="{{ route('admin.students.show', ['student' => $student->id]) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="px-6 py-4">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
