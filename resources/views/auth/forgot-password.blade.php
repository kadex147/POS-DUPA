<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Point Of Sale</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
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

        .btn-submit {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

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
    
    <div class="w-full lg:w-[380px] bg-white px-8 py-10 flex flex-col lg:shadow-2xl relative z-10 animate-slideInLeft">
        
        {{-- Logo --}}
        <div class="flex justify-center mb-8 logo-container">
            <img src="{{ asset('storage/images/logo.png') }}" 
                 alt="Logo" 
                 class="w-30 h-30 object-contain transition-transform duration-300"
                 onerror="this.onerror=null; this.style.display='none'; document.getElementById('logo-fallback').style.display='block';">
            
            <div id="logo-fallback" class="hidden w-20 h-20 bg-linear-to-br from-gray-500 to-gray-700 rounded-2xl items-center justify-center shadow-xl">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl text-gray-800 font-bold mb-1">Lupa Password</h1>
            <p class="text-sm text-gray-500">Masukkan email Anda untuk reset password</p>
        </div>

        {{-- Success Alert --}}
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
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            
            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <div class="input-with-icon">
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gray-500 bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-400 transition-all duration-300"
                           placeholder="Masukkan email Anda"
                           required 
                           autofocus>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="btn-submit w-full bg-linear-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-3.5 rounded-xl shadow-lg mt-6">
                Kirim Link Reset Password
            </button>
        </form>

        {{-- Back to Login --}}
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800 transition-colors duration-300 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Login
            </a>
        </div>

        {{-- Footer --}}
        <div class="mt-auto pt-8">
            <p class="text-xs text-gray-400 text-center">
                © 2025 Point Of Sale. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Image Section -->
    <div class="hidden lg:flex flex-1 login-image-container items-center justify-center relative animate-fadeIn">
        <img src="{{ asset('storage/images/login-bg.jpg') }}" 
             alt="Background" 
             class="login-image absolute inset-0 w-full h-full object-cover"
             onerror="this.style.display='none'; document.getElementById('fallback-image').style.display='flex';">
        
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