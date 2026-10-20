    {{-- ── Audience stats (entered by hand) ───────────────────────── --}}
    @php $audienceTotals = \App\Support\Seo::audience(); @endphp
    <div id="audience" class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fab fa-youtube text-red-500 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Audience Stats</h3>
                    <p class="text-xs text-gray-500">
                        YouTube subscribers and views for each channel — the site shows the totals on the homepage and footer
                        @if(!empty($audience['audience_updated_at'])) · last updated {{ \Illuminate\Support\Carbon::parse($audience['audience_updated_at'])->format('j M Y') }} @endif
                    </p>
                </div>
            </div>
        </div>

        @if(session('success_audience'))
            <div class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success_audience') }}
            </div>
        @endif

        <form action="{{ route('admin.content.audience') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach(\App\Support\Seo::AUDIENCE_CHANNELS as $key => $channel)
                    @php $channelUrl = $socials[$key === 'kn' ? 'youtube' : 'youtube_english'] ?? null; @endphp
                    <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-gray-700">{{ $channel['label'] }}</p>
                            @if($channelUrl)
                                <a href="{{ $channelUrl }}" target="_blank" rel="noopener" class="text-xs text-blue-600 hover:underline">
                                    {{ \App\Support\Seo::youtubeHandle($channelUrl) ?? 'Open channel' }} <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="audience_{{ $key }}_subscribers" class="block text-xs text-gray-500 mb-1">Subscribers</label>
                                <input type="number" min="0" step="1" id="audience_{{ $key }}_subscribers" name="audience_{{ $key }}_subscribers"
                                       value="{{ old("audience_{$key}_subscribers", $audience["audience_{$key}_subscribers"] ?? '') }}"
                                       placeholder="e.g. 5330"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold focus:ring-2 focus:ring-red-300 outline-none @error("audience_{$key}_subscribers") border-red-400 @enderror">
                                @error("audience_{$key}_subscribers")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="audience_{{ $key }}_views" class="block text-xs text-gray-500 mb-1">Total views</label>
                                <input type="number" min="0" step="1" id="audience_{{ $key }}_views" name="audience_{{ $key }}_views"
                                       value="{{ old("audience_{$key}_views", $audience["audience_{$key}_views"] ?? '') }}"
                                       placeholder="e.g. 80000"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold focus:ring-2 focus:ring-red-300 outline-none @error("audience_{$key}_views") border-red-400 @enderror">
                                @error("audience_{$key}_views")<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs text-gray-500">
                    @if($audienceTotals)
                        Shown on the site as <strong>{{ \App\Support\Seo::compact($audienceTotals['subscribers']) }} Subscribers</strong>
                        and <strong>{{ \App\Support\Seo::compact($audienceTotals['views']) }} Views</strong>, plus the live count of published episodes.
                    @else
                        Enter whole numbers (e.g. 5330, not 5.33K). The site rounds them for display.
                    @endif
                    Find them in YouTube Studio → Analytics, or the subscriber count on the channel page.
                </p>
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Audience Stats
                </button>
            </div>
        </form>
    </div>

