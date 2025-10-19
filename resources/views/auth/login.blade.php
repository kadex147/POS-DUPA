<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point Of Sale - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen">
    <!-- Login Container -->
    <div class="w-[275px] bg-gray-50 px-8 py-10 flex flex-col border-r border-gray-300">
        <h1 class="text-2xl text-gray-700 text-center mb-2">Point Of sale</h1>
        <p class="text-sm text-gray-600 text-center mb-10">login</p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-5">
                <label for="username" class="block text-sm text-gray-700 mb-2">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="{{ old('username') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-gray-500 bg-white"
                       required 
                       autofocus>
            </div>

            <div class="mb-5">
                <label for="password" class="block text-sm text-gray-700 mb-2">Password</label>
                <input type="password" 
                       id="password" 
                       name="password"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-gray-500 bg-white"
                       required>
            </div>

            <button type="submit" 
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white py-3 rounded transition mt-3">
                Login
            </button>
        </form>
    </div>

    <!-- Image Section -->
    <div class="flex-1 bg-gray-300 flex items-center justify-center">
        <div class="w-[300px] h-[220px] bg-gray-400 rounded-lg flex items-center justify-center relative">
            <!-- Mountain Shape -->
            <div class="absolute bottom-10 w-[150px] h-[100px] bg-white" 
                 style="clip-path: polygon(50% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);">
            </div>
            <!-- Circle (Sun/Moon) -->
            <div class="absolute top-12 right-16 w-5 h-5 bg-white rounded-full"></div>
        </div>
    </div>
</body>
</html>