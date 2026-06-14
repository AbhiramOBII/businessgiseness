<div
    x-data="{ showMessage: @entangle('message').live }"
    x-init="$watch('showMessage', val => {
        if (val) {
            setTimeout(() => $wire.clearMessage(), 5000);
        }
    })"
>
    <form wire:submit.prevent="subscribe" class="flex flex-col gap-3">
        <input
            type="email"
            wire:model="email"
            placeholder="Enter your email"
            class="px-3 sm:px-4 py-2 sm:py-3 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-brand-gold transition-colors text-sm sm:text-base w-full"
        >

        @error('email')
            <span class="text-red-400 text-xs">{{ $message }}</span>
        @enderror

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="bg-brand-gold hover:bg-yellow-600 text-brand-dark px-3 sm:px-4 py-2 sm:py-3 rounded font-semibold transition-colors text-sm sm:text-base disabled:opacity-50 disabled:cursor-not-allowed w-full"
        >
            <span wire:loading.remove>Subscribe</span>
            <span wire:loading class="flex items-center justify-center">
                <i class="fas fa-spinner fa-spin mr-2"></i>Subscribing...
            </span>
        </button>
    </form>

    @if($message)
        <div class="mt-3 p-3 rounded text-sm {{ $messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' }}">
            <i class="fas {{ $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-2"></i>
            {{ $message }}
        </div>
    @endif
</div>
