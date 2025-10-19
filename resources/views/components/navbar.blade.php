<header class="bg-white border-b border-gray-200 px-8 py-4">
    <div class="flex justify-end items-center">
        <!-- User Menu -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" 
                    class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                <div class="text-right">
                    <div class="text-sm font-semibold text-gray-800">{{ ucfirst(Auth::user()->role) }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->username }}</div>
                </div>
                <div class="w-9 h-9 bg-gray-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-cloak
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>