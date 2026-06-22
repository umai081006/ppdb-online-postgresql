<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Total Pendaftar</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Total Terverifikasi</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['verified'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Total Diterima</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['accepted'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Total Ditolak</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['rejected'] }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Grafik Pendaftaran Harian (30 Hari Terakhir)</h3>
                    <div class="relative h-96 w-full">
                        <canvas id="registrationChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-yellow-500">
                    <h3 class="text-lg font-bold mb-2">Sistem Seleksi</h3>
                    <p class="text-sm text-gray-600 mb-4">Jalankan proses seleksi pendaftar otomatis berdasarkan nilai, jalur, dan kuota.</p>
                    <a href="{{ route('admin.selection.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">Buka Seleksi</a>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-gray-800">
                    <h3 class="text-lg font-bold mb-2">Manajemen Panitia</h3>
                    <p class="text-sm text-gray-600 mb-4">Kelola akun panitia untuk membantu verifikasi dokumen siswa.</p>
                    <a href="{{ route('admin.panitia.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Kelola Panitia</a>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-blue-500">
                    <h3 class="text-lg font-bold mb-2">Pengumuman</h3>
                    <p class="text-sm text-gray-600 mb-4">Kelola pengumuman informasi PPDB ke halaman depan atau dashboard siswa.</p>
                    <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Kelola Pengumuman</a>
                </div>
            </div>

        </div>
    </div>

    <!-- Add Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('registrationChart').getContext('2d');
            const labels = {!! json_encode($labels) !!};
            const data = {!! json_encode($data) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Pendaftar Baru',
                        data: data,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
