<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - Business Giseness</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Satoshi Font -->
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#151828',
                        'brand-gold': '#ba933e',
                        'brand-white': '#FFFFFF'
                    },
                    fontFamily: {
                        'sans': ['Satoshi', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --brand-dark: #151828;
            --brand-gold: #ba933e;
            --brand-white: #FFFFFF;
        }
        
        body {
            font-family: 'Satoshi', system-ui, sans-serif;
        }
    </style>
    
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-brand-dark">
                    <i class="fas fa-cog text-brand-gold mr-2"></i>
                    Admin Panel
                </h2>
                <p class="text-sm text-gray-600 mt-1">Business Giseness</p>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</p>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-brand-gold hover:bg-opacity-10 hover:text-brand-gold transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-brand-gold bg-opacity-10 text-brand-gold border-r-2 border-brand-gold' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.content') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-brand-gold hover:bg-opacity-10 hover:text-brand-gold transition-colors duration-200 {{ request()->routeIs('admin.content') ? 'bg-brand-gold bg-opacity-10 text-brand-gold border-r-2 border-brand-gold' : '' }}">
                    <i class="fas fa-edit mr-3"></i>
                    Content Management
                </a>
                
                <a href="{{ route('admin.episodes.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-brand-gold hover:bg-opacity-10 hover:text-brand-gold transition-colors duration-200 {{ request()->routeIs('admin.episodes.*') ? 'bg-brand-gold bg-opacity-10 text-brand-gold border-r-2 border-brand-gold' : '' }}">
                    <i class="fas fa-podcast mr-3"></i>
                    Episodes
                </a>
                
                <a href="{{ route('admin.guests.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-brand-gold hover:bg-opacity-10 hover:text-brand-gold transition-colors duration-200 {{ request()->routeIs('admin.guests.*') ? 'bg-brand-gold bg-opacity-10 text-brand-gold border-r-2 border-brand-gold' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Guests
                </a>
                
                <a href="{{ route('admin.blog.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-brand-gold hover:bg-opacity-10 hover:text-brand-gold transition-colors duration-200 {{ request()->routeIs('admin.blog.*') ? 'bg-brand-gold bg-opacity-10 text-brand-gold border-r-2 border-brand-gold' : '' }}">
                    <i class="fas fa-blog mr-3"></i>
                    Blog Posts
                </a>
                
                <a href="{{ route('admin.newsletter-subscriptions.index') }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-brand-gold hover:bg-opacity-10 hover:text-brand-gold transition-colors duration-200 {{ request()->routeIs('admin.newsletter-subscriptions.*') ? 'bg-brand-gold bg-opacity-10 text-brand-gold border-r-2 border-brand-gold' : '' }}">
                    <i class="fas fa-envelope mr-3"></i>
                    Newsletter
                </a>
                
                <div class="px-6 py-2 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Website</p>
                </div>
                
                <a href="{{ route('home') }}" target="_blank" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-external-link-alt mr-3"></i>
                    View Website
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-600 mt-1">@yield('page-description', 'Welcome to the admin panel')</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- User Menu -->
                        <div class="relative">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                                <div class="w-8 h-8 bg-brand-gold rounded-full flex items-center justify-center">
                                    <span class="text-xs font-semibold text-brand-white">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
