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

                <!-- Search and Filter Section -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-1 md:col-span-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" id="search-input"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                                placeholder="Cari nama siswa...">
                        </div>
                    </div>

                    <div>
                        <select id="class-filter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Semua Kelas</option>
                            @php
                                // Extract unique classes from the results array
                                $classes = [];
                                foreach ($results as $result) {
                                    $className = $result['student']->classRoom->nama_kelas;
                                    if (!in_array($className, $classes)) {
                                        $classes[] = $className;
                                    }
                                }
                                sort($classes);
                            @endphp
                            @foreach ($classes as $class)
                                <option value="{{ $class }}">{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select id="status-filter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Semua Status</option>
                            <option value="1">Layak</option>
                            <option value="0">Tidak Layak</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <div id="filter-info" class="text-sm text-gray-600">
                        Menampilkan semua data
                    </div>
                    <form action="{{ route('admin.clustering.calculate') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Perbarui Hasil
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal" id="results-table">
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
                                <tr class="bg-white border-b border-gray-200"
                                    data-name="{{ strtolower($result['student']->nama) }}"
                                    data-class="{{ $result['student']->classRoom->nama_kelas }}"
                                    data-eligible="{{ $result['eligible'] ? '1' : '0' }}">
                                    <td class="px-5 py-5 text-sm row-number">{{ $index + 1 }}</td>
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

                    <!-- Empty results message -->
                    <div id="no-results" class="hidden py-8 text-center text-gray-500">
                        Tidak ada data yang sesuai dengan kriteria pencarian
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- JavaScript for filtering and search functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const classFilter = document.getElementById('class-filter');
            const statusFilter = document.getElementById('status-filter');
            const tableRows = document.querySelectorAll('#results-table tbody tr');
            const noResults = document.getElementById('no-results');
            const filterInfo = document.getElementById('filter-info');

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedClass = classFilter.value;
                const selectedStatus = statusFilter.value;

                let visibleCount = 0;

                tableRows.forEach(row => {
                    const name = row.getAttribute('data-name');
                    const classValue = row.getAttribute('data-class');
                    const eligible = row.getAttribute('data-eligible');

                    const matchesSearch = name.includes(searchTerm);
                    const matchesClass = selectedClass === '' || classValue === selectedClass;
                    const matchesStatus = selectedStatus === '' || eligible === selectedStatus;

                    const isVisible = matchesSearch && matchesClass && matchesStatus;

                    row.classList.toggle('hidden', !isVisible);

                    if (isVisible) {
                        visibleCount++;
                        // Update row numbers to be sequential for visible rows
                        row.querySelector('.row-number').textContent = visibleCount;
                    }
                });

                // Show/hide no results message
                noResults.classList.toggle('hidden', visibleCount > 0);

                // Update filter info text
                updateFilterInfo(visibleCount);
            }

            function updateFilterInfo(count) {
                let infoText = `Menampilkan ${count} data`;

                const filters = [];
                if (classFilter.value) {
                    filters.push(`kelas "${classFilter.value}"`);
                }
                if (statusFilter.value) {
                    const statusText = statusFilter.value === '1' ? 'Layak' : 'Tidak Layak';
                    filters.push(`status "${statusText}"`);
                }
                if (searchInput.value) {
                    filters.push(`pencarian "${searchInput.value}"`);
                }

                if (filters.length > 0) {
                    infoText += ` dengan filter: ${filters.join(', ')}`;
                }

                filterInfo.textContent = infoText;
            }

            // Event listeners
            searchInput.addEventListener('input', applyFilters);
            classFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
        });
    </script>
</x-admin-layout>
