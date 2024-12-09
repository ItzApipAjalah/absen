@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h4 class="text-xl font-semibold">Selamat Datang, {{ Auth::user()->name }}</h4>
        <p class="text-gray-400">Admin Dashboard</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-blue-600 rounded-lg shadow-lg p-6">
            <div class="flex flex-col">
                <p class="text-sm text-blue-100">Total Pegawai</p>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                <p class="text-sm text-blue-200 mt-2">Pegawai Aktif</p>
            </div>
        </div>

        <div class="bg-green-600 rounded-lg shadow-lg p-6">
            <div class="flex flex-col">
                <p class="text-sm text-green-100">Total Lokasi</p>
                <p class="text-3xl font-bold">{{ $totalLokasi }}</p>
                <p class="text-sm text-green-200 mt-2">Lokasi Terdaftar</p>
            </div>
        </div>

        <div class="bg-purple-600 rounded-lg shadow-lg p-6">
            <div class="flex flex-col">
                <p class="text-sm text-purple-100">Jadwal Aktif</p>
                <p class="text-3xl font-bold">{{ $activeJadwals }}</p>
                <p class="text-sm text-purple-200 mt-2">Jadwal Berjalan</p>
            </div>
        </div>

        <div class="bg-yellow-600 rounded-lg shadow-lg p-6">
            <div class="flex flex-col">
                <p class="text-sm text-yellow-100">Absensi Hari Ini</p>
                <p class="text-3xl font-bold">{{ $todayAbsens }}</p>
                <p class="text-sm text-yellow-200 mt-2">Total Check-in</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h5 class="text-lg font-semibold mb-4">Menu Cepat</h5>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('jadwal.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-calendar mr-2"></i> Kelola Jadwal
            </a>
            <a href="{{ route('lokasi.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-map-marker-alt mr-2"></i> Kelola Lokasi
            </a>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-users mr-2"></i> Kelola Pegawai
            </a>
            <a href="#" class="inline-flex items-center px-4 py-2 bg-yellow-600 rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-clock mr-2"></i> Laporan Absensi
            </a>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Recent Schedules -->
        <div class="md:col-span-2">
            <div class="bg-gray-800 rounded-lg shadow-lg">
                <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                    <h5 class="font-semibold">Jadwal Terbaru</h5>
                    <a href="{{ route('jadwal.index') }}" class="text-sm text-blue-400 hover:text-blue-300">
                        Lihat Semua
                    </a>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Hari</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pegawai</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jam</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($recentJadwals as $jadwal)
                                <tr class="hover:bg-gray-700">
                                    <td class="px-4 py-2">{{ $jadwal->hari }}</td>
                                    <td class="px-4 py-2">
                                        <div class="text-sm">
                                            @foreach($jadwal->lokasi->users as $user)
                                                <div>{{ $user->name }}</div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ $jadwal->lokasi->name }}</td>
                                    <td class="px-4 py-2">{{ $jadwal->start_time }} - {{ $jadwal->end_time }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $jadwal->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $jadwal->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Feed -->
        <div>
            <div class="bg-gray-800 rounded-lg shadow-lg">
                <div class="p-4 border-b border-gray-700">
                    <h5 class="font-semibold">Aktivitas Terkini</h5>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        @forelse($recentActivities ?? [] as $activity)
                        <div class="border-b border-gray-700 pb-4">
                            <p class="text-sm text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                            <p class="mt-1">{{ $activity->description }}</p>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Tidak ada aktivitas terbaru</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.activity-feed .feed-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>
@endpush
