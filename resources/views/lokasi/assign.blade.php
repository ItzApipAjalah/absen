@extends('layouts.app')

@section('title', 'Assign Petugas')

@section('content')
<div class="space-y-6">
    <div class="bg-gray-800 rounded-lg shadow-lg">
        <div class="p-4 border-b border-gray-700">
            <h5 class="font-semibold text-xl">Assign Petugas - {{ $lokasi->name }}</h5>
        </div>
        <div class="p-6">
            <form action="{{ route('lokasi.assign-petugas', $lokasi->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Pilih Petugas</label>
                        <select name="petugas_ids[]" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" multiple required>
                            @foreach($petugas as $p)
                                <option value="{{ $p->id }}"
                                    {{ $lokasi->petugas->contains('id', $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-gray-400">Tahan Ctrl/Cmd untuk memilih lebih dari satu</small>
                    </div>
                    <div class="flex justify-end space-x-2 pt-4">
                        <a href="{{ route('lokasi.index') }}" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
