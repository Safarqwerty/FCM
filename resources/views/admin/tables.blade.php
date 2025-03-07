<x-admin-layout>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-between mb-4">
            <h1 class="text-gray-700 text-3xl font-medium">Hasil Clustering Beasiswa</h1>
        </div>

        @if (isset($message))
            <div class="alert alert-info">{{ $message }}</div>
        @endif

        @if (isset($stats))
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 bg-blue-100 border-l-4 border-blue-500">
                    <h3 class="text-blue-700 font-bold">Total Siswa</h3>
                    <p class="text-gray-800 text-lg">{{ $stats['total_students'] }}</p>
                </div>
                <div class="p-4 bg-green-100 border-l-4 border-green-500">
                    <h3 class="text-green-700 font-bold">Siswa Layak</h3>
                    <p class="text-gray-800 text-lg">{{ $stats['eligible_count'] }}
                        ({{ $stats['eligible_percentage'] }}%)</p>
                </div>
                <div class="p-4 bg-red-100 border-l-4 border-red-500">
                    <h3 class="text-red-700 font-bold">Siswa Tidak Layak</h3>
                    <p class="text-gray-800 text-lg">{{ $stats['not_eligible_count'] }}
                        ({{ $stats['not_eligible_percentage'] }}%)</p>
                </div>
                <div class="p-4 bg-gray-100 border-l-4 border-gray-500">
                    <h3 class="text-gray-700 font-bold">Jumlah Iterasi</h3>
                    <p class="text-gray-800 text-lg">{{ $iteration_count }}</p>
                </div>
            </div>

            <div class="mt-6 bg-white shadow rounded-lg p-4">
                <h6 class="text-lg font-bold text-gray-700 mb-4">Hasil Clustering Beasiswa</h6>
                <div class="overflow-x-auto">
                    <form action="{{ route('admin.clustering.calculate') }}" method="POST" class="ml-auto mb-6">
                        @csrf
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Perbarui Hasil
                        </button>
                    </form>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nama Siswa</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Kelas</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Cluster</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nilai C1</th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nilai C2</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $index => $result)
                                <tr class="bg-white border-b border-gray-200">
                                    <td class="px-5 py-5 text-sm">{{ $index + 1 }}</td>
                                    <td class="px-5 py-5 text-sm">{{ $result['student']->nama }}</td>
                                    <td class="px-5 py-5 text-sm">{{ $result['student']->classRoom->nama_kelas }}</td>
                                    <td class="px-5 py-5 text-sm">Cluster {{ $result['cluster'] + 1 }}</td>
                                    <td class="px-5 py-5 text-sm">
                                        @if ($result['eligible'])
                                            <span
                                                class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                                <span class="relative">Layak</span>
                                            </span>
                                        @else
                                            <span
                                                class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                                <span class="relative">Tidak Layak</span>
                                            </span>
                                        @endif
                                    </td>
                                    @foreach ($result['membership_values'] as $index => $memberships)
                                        <td class="px-5 py-5 text-sm">
                                            {{ number_format($memberships, 4) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
