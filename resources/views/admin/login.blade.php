<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — Business Giseness</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Satoshi', system-ui, sans-serif; }
        .bg-brand-dark  { background-color: #151828; }
        .bg-brand-gold  { background-color: #ba933e; }
        .text-brand-gold { color: #ba933e; }
        .text-brand-dark { color: #151828; }
        .border-brand-gold { border-color: #ba933e; }
        .focus\:border-brand-gold:focus { border-color: #ba933e; }
        .focus\:ring-brand-gold:focus { --tw-ring-color: #ba933e; }
        .hover\:bg-yellow-400:hover { background-color: #facc15; }

        .grid-bg {
            background-image:
                linear-gradient(rgba(186,147,62,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(186,147,62,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>

<body class="bg-brand-dark min-h-screen grid-bg flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo + Branding --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-4">
                <img src="{{ asset('images/logo-english-white-gold.png') }}"
                     alt="Business Giseness"
                     class="h-20 w-auto mx-auto object-contain">
            </a>
            <h1 class="text-white text-2xl font-bold tracking-tight">BG Admin Portal</h1>
            <p class="text-gray-500 text-sm mt-1">Restricted access. Authorised personnel only.</p>
        </div>

        {{-- Card --}}
        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-8 shadow-2xl">

            {{-- Card Header --}}
            <div class="flex items-center gap-3 mb-7 pb-6 border-b border-white/10">
                <div class="w-10 h-10 bg-brand-gold/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-brand-gold"></i>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">Secure Sign In</p>
                    <p class="text-gray-500 text-xs">Business Giseness Administration</p>
                </div>
            </div>

            {{-- Livewire Login Form --}}
            @livewire('admin-login')

        </div>

        {{-- Footer --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}"
               class="text-gray-600 hover:text-gray-400 text-xs transition-colors">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to website
            </a>
        </div>

    </div>

    @livewireScripts
</body>
</html>
