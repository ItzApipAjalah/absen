@extends('layouts.app')

@section('title', 'Manajemen Jadwal')

@section('content')
<div class="space-y-6">
    <div class="bg-gray-800 rounded-lg shadow-lg">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h5 class="font-semibold text-xl">Manajemen Jadwal</h5>
            <a href="{{ route('jadwal.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Tambah Jadwal
            </a>
        </div>
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-500 text-white px-4 py-2 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Hari</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Lokasi</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pegawai</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jam Mulai</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Jam Selesai</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Toleransi (Menit)</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($jadwals as $jadwal)
                        <tr class="hover:bg-gray-700">
                            <td class="px-4 py-2">{{ $jadwal->hari }}</td>
                            <td class="px-4 py-2">{{ $jadwal->lokasi->name }}</td>
                            <td class="px-4 py-2">
                                <div class="text-sm space-y-1">
                                    @foreach($jadwal->lokasi->users as $user)
                                        <div>{{ $user->name }}</div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-2">{{ $jadwal->start_time }}</td>
                            <td class="px-4 py-2">{{ $jadwal->end_time }}</td>
                            <td class="px-4 py-2">{{ $jadwal->late_tolerance }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $jadwal->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ $jadwal->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $hasAccess = Auth::user()->isAdmin() ||
                                        DB::table('petugas_lokasis')
                                            ->where('user_id', Auth::id())
                                            ->where('lokasi_id', $jadwal->lokasi_id)
                                            ->exists();
                                @endphp

                                @if($hasAccess)
                                <div class="flex space-x-2">
                                    <a href="{{ route('jadwal.edit', $jadwal->id) }}"
                                       class="inline-flex items-center px-2 py-1 bg-yellow-600 rounded hover:bg-yellow-700 transition-colors text-sm">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('jadwal.destroy', $jadwal->id) }}"
                                          method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-2 py-1 bg-red-600 rounded hover:bg-red-700 transition-colors text-sm"
                                                onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
