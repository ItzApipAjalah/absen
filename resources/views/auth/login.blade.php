<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full mx-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold">Sistem Absensi</h2>
            <p class="text-gray-400 mt-2">Silakan login untuk melanjutkan</p>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            @if(session('error'))
                <div class="mb-4 bg-red-500 text-white px-4 py-2 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email"
                                   class="w-full bg-gray-700 border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:border-blue-500 focus:ring-blue-500"
                                   required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password"
                                   class="w-full bg-gray-700 border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:border-blue-500 focus:ring-blue-500"
                                   required>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember"
                                   class="bg-gray-700 border-gray-600 rounded text-blue-500 focus:ring-blue-500">
                            <label for="remember" class="ml-2 text-sm text-gray-400">Ingat saya</label>
                        </div>
                        <a href="#" class="text-sm text-blue-500 hover:text-blue-400">Lupa password?</a>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Login
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm text-gray-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-400">Daftar disini</a>
            </div>
        </div>
    </div>
</body>
</html>
