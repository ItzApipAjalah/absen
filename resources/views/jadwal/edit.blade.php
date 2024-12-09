@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<div class="space-y-6">
    <div class="bg-gray-800 rounded-lg shadow-lg">
        <div class="p-4 border-b border-gray-700">
            <h5 class="font-semibold text-xl">Edit Jadwal</h5>
        </div>
        <div class="p-6">
            <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Hari</label>
                        <select name="hari" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <option value="{{ $hari }}" {{ $jadwal->hari === $hari ? 'selected' : '' }}>
                                    {{ $hari }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Lokasi</label>
                        <select name="lokasi_id" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" {{ $jadwal->lokasi_id === $lokasi->id ? 'selected' : '' }}>
                                    {{ $lokasi->name }} ({{ $lokasi->users->count() }} pegawai)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" value="{{ $jadwal->start_time }}" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" value="{{ $jadwal->end_time }}" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Toleransi Keterlambatan (Menit)</label>
                        <input type="number" name="late_tolerance" value="{{ $jadwal->late_tolerance }}" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                        <select name="status" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                            <option value="active" {{ $jadwal->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $jadwal->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2 pt-4">
                        <a href="{{ route('jadwal.index') }}" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
