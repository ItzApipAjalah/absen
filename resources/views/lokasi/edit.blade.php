@extends('layouts.app')

@section('title', 'Edit Lokasi')

@section('content')
<div class="space-y-6">
    <div class="bg-gray-800 rounded-lg shadow-lg">
        <div class="p-4 border-b border-gray-700">
            <h5 class="font-semibold text-xl">Edit Lokasi</h5>
        </div>
        <div class="p-6">
            <form action="{{ route('lokasi.update', $lokasi->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Nama Lokasi</label>
                        <input type="text" name="name" value="{{ $lokasi->name }}" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Latitude</label>
                        <input type="number" name="latitude" value="{{ $lokasi->latitude }}" step="any" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                        <small class="text-gray-400">Contoh: -6.2088</small>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Longitude</label>
                        <input type="number" name="longitude" value="{{ $lokasi->longitude }}" step="any" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                        <small class="text-gray-400">Contoh: 106.8456</small>
                    </div>
                    <div class="flex justify-end space-x-2 pt-4">
                        <a href="{{ route('lokasi.index') }}" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
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
