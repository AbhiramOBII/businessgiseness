<div>
    <form wire:submit="login" novalidate>

        {{-- Error Banner --}}
        @if ($errorMessage)
            <div class="mb-5 flex items-start gap-3 bg-red-500/10 border border-red-500/30 text-red-400 text-sm px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        {{-- Email --}}
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                Email Address
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                    <i class="fas fa-envelope text-sm"></i>
                </span>
                <input
                    wire:model="email"
                    id="email"
                    type="email"
                    autocomplete="email"
                    autofocus
                    placeholder="admin@example.com"
                    class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors"
                >
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                Password
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input
                    wire:model="password"
                    id="password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors"
                >
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center justify-between mb-7">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input
                    wire:model="remember"
                    type="checkbox"
                    class="w-4 h-4 rounded border-white/20 bg-white/5 text-brand-gold focus:ring-brand-gold focus:ring-offset-0"
                >
                <span class="text-sm text-gray-400">Remember me</span>
            </label>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full bg-brand-gold hover:bg-yellow-400 text-brand-dark font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
        >
            <span wire:loading.remove wire:target="login">
                <i class="fas fa-sign-in-alt mr-1"></i>
                Sign In to Admin
            </span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Authenticating...
            </span>
        </button>

    </form>
</div>
