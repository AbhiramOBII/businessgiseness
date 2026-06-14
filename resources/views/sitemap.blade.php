{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
    {{-- Static important pages --}}
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
        <xhtml:link rel="alternate" hreflang="en" href="{{ route('home') }}"/>
        <xhtml:link rel="alternate" hreflang="kn" href="{{ route('home') }}"/>
    </url>

    <url>
        <loc>{{ route('about-business-giseness-podcast') }}</loc>
        <lastmod>{{ now()->startOfMonth()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route('episodes.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('guests.index') }}</loc>
        <lastmod>{{ now()->startOfWeek()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>{{ route('blog.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>{{ route('privacy-policy') }}</loc>
        <lastmod>2025-01-01T00:00:00+00:00</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ route('terms-of-use') }}</loc>
        <lastmod>2025-01-01T00:00:00+00:00</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ route('disclaimer') }}</loc>
        <lastmod>2025-01-01T00:00:00+00:00</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- Dynamic episode pages --}}
    @foreach ($episodes as $episode)
        <url>
            <loc>{{ route('episodes.show', $episode) }}</loc>
            @php
                $lastMod = $episode->updated_at ?: $episode->published_at;
            @endphp
            @if ($lastMod)
                <lastmod>{{ $lastMod->tz('UTC')->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    {{-- Dynamic guest pages --}}
    @foreach ($guests as $guest)
        <url>
            <loc>{{ route('guests.show', $guest) }}</loc>
            @if ($guest->updated_at)
                <lastmod>{{ $guest->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach

    {{-- Dynamic blog post pages --}}
    @foreach ($blogPosts as $blogPost)
        <url>
            <loc>{{ route('blog.show', $blogPost) }}</loc>
            @php
                $lastMod = $blogPost->updated_at ?: $blogPost->published_at;
            @endphp
            @if ($lastMod)
                <lastmod>{{ $lastMod->tz('UTC')->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
