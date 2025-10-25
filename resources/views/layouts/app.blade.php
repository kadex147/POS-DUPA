<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Point of Sale')</title>
    
    {{-- Vite Assets - Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Alpine.js for interactivity --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen" x-data="{ sidebarOpen: false }">
        
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" 
             :class="{ 'active': sidebarOpen }"
             @click="sidebarOpen = false"></div>
        
        <!-- Sidebar -->
        <aside class="w-60 bg-gray-50 border-r border-gray-200 fixed lg:static h-full z-50 sidebar-transition"
               :class="{ '-translate-x-full lg:translate-x-0': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <!-- ... rest of sidebar ... -->
            <div class="p-6 border-b border-gray-200 text-center">
                <h2 class="text-lg font-semibold text-gray-700">Dupa Radha Kresna</h2>
                <p class="text-sm text-gray-500 mt-1">Point of Sale</p>
            </div>

            <nav class="py-4">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('dashboard') ? 'bg-gray-200' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('users.index') }}" 
                        class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('users.*') ? 'bg-gray-200' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        <span>Data User</span>
                    </a>

                    <a href="{{ route('categories.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('categories.*') ? 'bg-gray-200' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                        </svg>
                        <span>Data Kategori</span>
                    </a>

                    <a href="{{ route('products.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('products.*') ? 'bg-gray-200' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16z"/>
                        </svg>
                        <span>Data Produk</span>
                    </a>
                @endif

                <a href="{{ route('pos.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('pos.*') ? 'bg-gray-200' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                    <span>POS</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            
            <!-- Header/Navbar -->
            <header class="bg-white border-b border-gray-200 px-4 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <!-- Hamburger Menu (Mobile Only) -->
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative ml-auto">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 lg:gap-3 px-2 lg:px-3 py-2 rounded-lg hover:bg-gray-50 transition">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-semibold text-gray-800">{{ ucfirst(Auth::user()->role) }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->username }}</div>
                            </div>
                            <div class="w-9 h-9 bg-gray-600 rounded-full flex items-center justify-center flex-shrink-0">
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
                            <div class="px-4 py-2 border-b sm:hidden">
                                <div class="text-sm font-semibold text-gray-800">{{ ucfirst(Auth::user()->role) }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->username }}</div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 lg:p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>