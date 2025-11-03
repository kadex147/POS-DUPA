<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point Of Sale - Login</title>
    
    {{-- Vite Assets - Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Smooth fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }

        /* Background image dengan opacity */
        .login-image-container {
            position: relative;
            overflow: hidden;
        }
        
        .login-image-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(107, 114, 128, 0.1) 0%, rgba(156, 163, 175, 0.2) 100%);
            z-index: 1;
        }
        
        .login-image {
            opacity: 0.75;
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        
        /* Soft shadow untuk card */
        .soft-card {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .soft-card:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        }

        /* Input smooth focus */
        .input-smooth {
            transition: all 0.3s ease;
        }

        .input-smooth:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.1);
        }

        /* Button smooth hover */
        .btn-smooth {
            transition: all 0.3s ease;
        }

        .btn-smooth:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }

        .btn-smooth:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="flex min-h-screen bg-gray-50">
    
    <!-- Login Container - Soft & Modern -->
    <div class="w-full lg:w-[320px] bg-white px-8 py-10 flex flex-col lg:shadow-2xl relative z-10 animate-fadeIn">
        
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('storage/images/logo.png') }}" 
                 alt="Logo" 
                 class="w-16 h-16 object-contain transition-transform hover:scale-105 duration-300"
                 onerror="this.onerror=null; this.style.display='none'; document.getElementById('logo-fallback').style.display='block';">
            
            {{-- Fallback jika logo tidak ada --}}
            <div id="logo-fallback" class="hidden w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-xl">POS</span>
            </div>
        </div>

        {{-- Title --}}
        <h1 class="text-2xl text-gray-800 font-semibold text-center mb-1">Point Of Sale</h1>
        <p class="text-sm text-gray-500 text-center mb-8">login</p>

        {{-- Error Alert dengan rounded soft --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm animate-fadeIn">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-5">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username') }}"
                       class="input-smooth w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none bg-white text-gray-900 placeholder-gray-400"
                       placeholder="Masukkan username"
                       required 
                       autofocus>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="input-smooth w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none bg-white text-gray-900 placeholder-gray-400"
                       placeholder="Masukkan password"
                       required>
            </div>

            <button type="submit" 
                    class="btn-smooth w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 rounded-xl shadow-md">
                Login
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-auto pt-8">
            <p class="text-xs text-gray-400 text-center">
                © 2025 Point Of Sale
            </p>
        </div>
    </div>

    <!-- Image Section - Hidden on Mobile, Visible on Desktop -->
    <div class="hidden lg:flex flex-1 login-image-container items-center justify-center relative">
        <img src="{{ asset('storage/images/login-bg.jpg') }}" 
             alt="Background" 
             class="login-image absolute inset-0 w-full h-full object-cover"
             onerror="this.style.display='none'; document.getElementById('fallback-image').style.display='flex';">
        
        {{-- Fallback jika gambar tidak ditemukan --}}
        <div id="fallback-image" class="absolute inset-0 bg-gray-300 items-center justify-center hidden">
            <div class="text-center p-8">
                <svg class="w-32 h-32 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 text-sm">
                    Letakkan foto Anda di:<br>
                    <code class="bg-gray-200 px-2 py-1 rounded text-xs mt-2 inline-block">storage/app/public/images/login-bg.jpg</code>
                </p>
            </div>
        </div>
    </div>
</body>
</html>