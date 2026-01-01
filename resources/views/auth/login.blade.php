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
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-slideInLeft {
            animation: slideInLeft 0.6s ease-out;
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
            background: linear-gradient(135deg, rgba(107, 114, 128, 0.15) 0%, rgba(156, 163, 175, 0.25) 100%);
            z-index: 1;
        }
        
        .login-image {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        
        /* Input dengan icon styling */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        .input-with-icon input {
            padding-left: 44px;
        }

        .input-with-icon input:focus ~ svg {
            color: #6b7280;
        }

        /* Button modern hover */
        .btn-login {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Logo pulse animation on hover */
        .logo-container:hover img,
        .logo-container:hover div {
            animation: pulse 0.6s ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body class="flex min-h-screen bg-gray-50">
    
    <!-- Login Container - Modern & Clean -->
    <div class="w-full lg:w-[380px] bg-white px-8 py-10 flex flex-col lg:shadow-2xl relative z-10 animate-slideInLeft">
        
        {{-- Logo --}}
        <div class="flex justify-center mb-8 logo-container">
            <img src="{{ asset('storage/images/logo.png') }}" 
                 alt="Logo" 
                 class="w-30 h-30 object-contain transition-transform duration-300"
                 onerror="this.onerror=null; this.style.display='none'; document.getElementById('logo-fallback').style.display='block';">
            
            {{-- Fallback jika logo tidak ada --}}
            <div id="logo-fallback" class="hidden w-20 h-20 bg-linear-to-br from-gray-500 to-gray-700 rounded-2xl items-center justify-center shadow-xl">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl text-gray-800 font-bold mb-1">Point Of Sale</h1>
            <p class="text-sm text-gray-500">Masuk ke akun Anda</p>
        </div>

        {{-- Success Alert (untuk pesan setelah reset password) --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm animate-fadeIn">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm animate-fadeIn">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <!-- Username Input dengan Icon -->
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <div class="input-with-icon">
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="{{ old('username') }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gray-500 bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-400 transition-all duration-300"
                           placeholder="Masukkan username"
                           required 
                           autofocus>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- Password Input dengan Icon -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <div class="input-with-icon">
                    <input type="password" 
                           id="password" 
                           name="password"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gray-500 bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-400 transition-all duration-300"
                           placeholder="Masukkan password"
                           required>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- Forgot Password Link -->
            <div class="text-right">
                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-gray-800 hover:underline transition-colors duration-300">
                    Lupa Password?
                </a>
            </div>

            <!-- Login Button -->
            <button type="submit" 
                    class="btn-login w-full bg-linear-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-3.5 rounded-xl shadow-lg mt-6">
                Masuk
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-auto pt-8">
            <p class="text-xs text-gray-400 text-center">
                © 2025 Point Of Sale. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Image Section - Hidden on Mobile, Visible on Desktop -->
    <div class="hidden lg:flex flex-1 login-image-container items-center justify-center relative animate-fadeIn">
        <img src="{{ asset('storage/images/login-bg.jpg') }}" 
             alt="Background" 
             class="login-image absolute inset-0 w-full h-full object-cover"
             onerror="this.style.display='none'; document.getElementById('fallback-image').style.display='flex';">
        
        {{-- Fallback jika gambar tidak ditemukan --}}
        <div id="fallback-image" class="absolute inset-0 bg-linear-to-br from-gray-200 via-gray-300 to-gray-400 items-center justify-center hidden">
            <div class="text-center p-8">
                <svg class="w-32 h-32 mx-auto text-white mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-white text-lg font-semibold mb-2">Background Image</p>
                <p class="text-gray-200 text-sm max-w-xs mx-auto">
                    Letakkan foto Anda di:<br>
                    <code class="bg-white/20 px-3 py-1 rounded text-xs mt-2 inline-block">storage/app/public/images/login-bg.jpg</code>
                </p>
            </div>
        </div>
    </div>
</body>
</html>