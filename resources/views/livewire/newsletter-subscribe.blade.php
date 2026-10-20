<div
    x-data="{ showMessage: @entangle('message').live }"
    x-init="$watch('showMessage', val => {
        if (val) {
            setTimeout(() => $wire.clearMessage(), 5000);
        }
    })"
>
    <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-3">
        <label for="newsletter-email" class="sr-only">Email address</label>
        <input
            id="newsletter-email"
            type="email"
            wire:model="email"
            placeholder="Your email address"
            autocomplete="email"
            class="flex-1 min-w-0 px-4 py-3 bg-white/5 border border-white/15 rounded-full text-white placeholder-gray-500 focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors text-sm"
        >

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="inline-flex items-center justify-center gap-2 bg-brand-gold hover:bg-yellow-500 text-brand-dark px-6 py-3 rounded-full font-semibold transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap"
        >
            <span wire:loading.remove wire:target="subscribe">Subscribe</span>
            <span wire:loading wire:target="subscribe" class="flex items-center justify-center">
                <i class="fas fa-spinner fa-spin mr-2"></i>Subscribing...
            </span>
        </button>
    </form>

    @error('email')
        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
    @enderror

    @if($message)
        <div class="mt-3 p-3 rounded-lg text-sm {{ $messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' }}" role="status">
            <i class="fas {{ $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-2"></i>
            {{ $message }}
        </div>
    @endif
</div>
