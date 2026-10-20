<article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-lg transition-shadow">
    <a href="{{ route('episodes.show', $episode) }}" class="block">
        <div class="aspect-video bg-gray-200 relative overflow-hidden">
            @if($episode->image_url)
                <img src="{{ $episode->image_url }}" alt="{{ $episode->title }}" loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-gold to-yellow-600">
                    <i class="fas fa-podcast text-4xl text-white" aria-hidden="true"></i>
                </div>
            @endif
            <div class="absolute bottom-3 left-3 flex gap-2">
                @if($episode->published_at)
                    <span class="bg-black/75 text-white text-xs px-2 py-1 rounded">{{ $episode->formatted_published_at }}</span>
                @endif
                @if($episode->language_label)
                    <span class="bg-brand-gold text-brand-dark text-xs font-semibold px-2 py-1 rounded">{{ $episode->language_label }}</span>
                @endif
            </div>
        </div>
        <div class="p-5">
            <h3 class="font-bold text-brand-dark leading-snug line-clamp-2 group-hover:text-brand-gold transition-colors">{{ $episode->title }}</h3>
            <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $episode->short_description }}</p>
        </div>
    </a>
</article>
