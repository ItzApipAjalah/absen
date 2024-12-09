@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="space-y-6">
    <div class="bg-gray-800 rounded-lg shadow-lg">
        <div class="p-4 border-b border-gray-700">
            <h5 class="font-semibold text-xl">Edit Pegawai</h5>
        </div>
        <div class="p-6">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                        <input type="password" name="password" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white">
                        <small class="text-gray-400">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Lokasi</label>
                        <select name="lokasi_id" class="w-full bg-gray-700 border-gray-600 rounded-lg text-white" required>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" {{ $user->lokasi_id == $lokasi->id ? 'selected' : '' }}>
                                    {{ $lokasi->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2 pt-4">
                        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
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
