<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Point of Sale')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        
        <!-- Mobile Overlay -->
        <div id="overlay" 
             class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden backdrop-blur-sm"
             onclick="toggleSidebar()">
        </div>
        
        <!-- Sidebar dengan Soft Design -->
        <aside id="sidebar"
               class="sidebar-soft w-60 fixed lg:static h-full z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            
            <!-- Logo Section dengan Soft Card -->
            <div class="p-6 text-center border-b border-gray-100">
                <!-- Logo Image -->
                <div class="inline-block mb-3 relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-400/20 to-gray-600/20 rounded-2xl blur-lg group-hover:blur-xl transition-all duration-300"></div>
                    <img src="{{ asset('storage/images/logo.png') }}" 
                         alt="Logo" 
                         class="relative w-16 h-16 object-contain rounded-2xl shadow-lg transition-transform duration-300 group-hover:scale-105"
                         onerror="this.onerror=null; this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">
                    
                    <!-- Fallback Logo jika gambar tidak ada -->
                    <div id="logo-fallback" 
                         class="hidden relative w-16 h-16 bg-gradient-to-br from-gray-600 to-gray-800 rounded-2xl items-center justify-center shadow-lg">
                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-lg font-bold text-gray-800">Dupa Radha Kresna</h2>
                <p class="text-xs text-gray-500 mt-1 font-medium">Point of Sale</p>
            </div>

            <!-- Navigation dengan Soft Links -->
            <nav class="py-4">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center text-gray-700 hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ request()->routeIs('dashboard') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                            </svg>
                        </div>
                        <span class="ml-3 font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('users.index') }}" 
                       class="flex items-center text-gray-700 hover:text-gray-900 {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ request()->routeIs('users.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </div>
                        <span class="ml-3 font-medium">Data User</span>
                    </a>

                    <a href="{{ route('categories.index') }}" 
                       class="flex items-center text-gray-700 hover:text-gray-900 {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ request()->routeIs('categories.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                            </svg>
                        </div>
                        <span class="ml-3 font-medium">Data Kategori</span>
                    </a>

                    <a href="{{ route('products.index') }}" 
                       class="flex items-center text-gray-700 hover:text-gray-900 {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ request()->routeIs('products.*') ? 'bg-white/20' : '' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16z"/>
                            </svg>
                        </div>
                        <span class="ml-3 font-medium">Data Produk</span>
                    </a>
                @endif

                <a href="{{ route('pos.index') }}" 
                   class="flex items-center text-gray-700 hover:text-gray-900 {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ request()->routeIs('pos.*') ? 'bg-white/20' : '' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </div>
                    <span class="ml-3 font-medium">POS</span>
                </a>
            </nav>

            <!-- Footer Sidebar (Optional) -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">
                <div class="text-center text-xs text-gray-400">
                    <p>© 2025 POS System</p>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header/Navbar dengan Soft Design -->
            <header class="bg-white border-b border-gray-100 px-4 lg:px-8 py-4 shadow-sm">
                <div class="flex justify-between items-center">
                    <!-- Hamburger Menu (Mobile Only) -->
                    <button onclick="toggleSidebar()" 
                            class="lg:hidden p-2.5 rounded-xl hover:bg-gray-50 transition-all duration-200 active:scale-95">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title (Optional - Hidden on Mobile) -->
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold text-gray-800">@yield('page-title', '')</h1>
                    </div>

                    <!-- User Menu dengan Alpine.js -->
                    <div x-data="{ open: false }" class="relative ml-auto">
                        <button @click="open = !open" 
                                type="button"
                                class="flex items-center gap-2 lg:gap-3 px-3 lg:px-4 py-2.5 rounded-xl hover:bg-gray-50 transition-all duration-200 active:scale-95 border border-gray-100">
                            <!-- Info User -->
                            <div class="text-right">
                                <div class="text-xs lg:text-sm font-bold text-gray-800">{{ ucfirst(Auth::user()->role) }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->username }}</div>
                            </div>
                            <!-- Avatar Bulat dengan Gradient -->
                            <div class="w-10 h-10 lg:w-11 lg:h-11 bg-gradient-to-br from-gray-600 to-gray-800 rounded-full flex items-center justify-center shadow-lg ring-2 ring-gray-200 ring-offset-2">
                                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                        </button>

                        <!-- Dropdown Menu dengan Soft Design -->
                        <div x-show="open" 
                             @click.outside="open = false"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50 overflow-hidden">
                            
                            <!-- User Info in Dropdown -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->username }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                            </div>

                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200 flex items-center gap-3 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 lg:p-8 overflow-y-auto bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- JavaScript untuk Toggle Sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            
            // Prevent body scroll when sidebar is open on mobile
            if (!sidebar.classList.contains('-translate-x-full')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('overlay');
            overlay.addEventListener('click', function() {
                toggleSidebar();
            });
        });

        // Close sidebar when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');
                if (!sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>