{{-- ── Join BG Community (membership registration) ─────────────── --}}
@php
    use App\Models\CommunityMember;

    $field = 'w-full rounded-lg border px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-brand-gold focus:border-brand-gold';
    $chosen = old('expectations', []);
@endphp

<section id="join" class="py-20 bg-brand-dark text-white scroll-mt-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 grid gap-12 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <p class="text-brand-gold text-xs font-semibold uppercase tracking-[0.2em]">Become a member</p>
            <h2 class="mt-3 text-3xl md:text-4xl font-bold leading-tight">Register to join BG Community</h2>
            <p class="mt-4 text-white/70 leading-relaxed">
                Tell us about your business and what you're looking for. We use this to invite you to the right meetups
                and introduce you to business owners who could be your next customer, vendor or partner.
            </p>
            <ul class="mt-8 space-y-3 text-sm text-white/70">
                <li class="flex gap-3"><i class="fas fa-check text-brand-gold mt-1" aria-hidden="true"></i>Free to join</li>
                <li class="flex gap-3"><i class="fas fa-check text-brand-gold mt-1" aria-hidden="true"></i>Invites to members-first networking meetups</li>
                <li class="flex gap-3"><i class="fas fa-check text-brand-gold mt-1" aria-hidden="true"></i>Introductions based on your vertical and goals</li>
                <li class="flex gap-3"><i class="fas fa-check text-brand-gold mt-1" aria-hidden="true"></i>We confirm your email before you're added</li>
            </ul>
        </div>

        <div class="lg:col-span-3">
            <div class="rounded-2xl bg-white text-brand-dark p-6 sm:p-8 shadow-xl">
                @if(session('member_pending'))
                    <div class="text-center py-8" role="status">
                        <span class="mx-auto w-14 h-14 rounded-full bg-brand-gold/15 text-brand-gold flex items-center justify-center text-2xl">
                            <i class="fas fa-envelope-open-text" aria-hidden="true"></i>
                        </span>
                        <h3 class="mt-4 text-2xl font-bold">Check your email</h3>
                        <p class="mt-2 text-gray-600 max-w-md mx-auto">
                            We've sent a confirmation link to <strong>{{ session('member_pending') }}</strong>.
                            Click it to complete your registration. Can't find it? Check your spam or promotions folder.
                        </p>
                    </div>
                @elseif(session('member_exists'))
                    <div class="text-center py-8" role="status">
                        <span class="mx-auto w-14 h-14 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-2xl">
                            <i class="fas fa-user-check" aria-hidden="true"></i>
                        </span>
                        <h3 class="mt-4 text-2xl font-bold">You're already a member</h3>
                        <p class="mt-2 text-gray-600">This email is already registered and confirmed with BG Community.</p>
                    </div>
                @else
                    <h3 class="text-xl font-bold">Your details</h3>
                    <p class="mt-1 text-sm text-gray-500">All fields except the message are required.</p>

                    @if(session('member_mail_failed'))
                        <p class="mt-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2" role="alert">
                            We saved your details but couldn't send the confirmation email just now. Please try again in a few minutes.
                        </p>
                    @endif

                    <form action="{{ route('community.members.store') }}" method="POST" class="mt-6 space-y-5" novalidate>
                        @csrf
                        <div class="hidden" aria-hidden="true">
                            <label for="join-website">Website</label>
                            <input type="text" id="join-website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            @foreach([
                                ['name' => 'name',    'label' => 'Full name',    'type' => 'text',  'autocomplete' => 'name',         'placeholder' => 'Your name'],
                                ['name' => 'email',   'label' => 'Email ID',     'type' => 'email', 'autocomplete' => 'email',        'placeholder' => 'you@company.com'],
                                ['name' => 'phone',   'label' => 'Phone number', 'type' => 'tel',   'autocomplete' => 'tel',          'placeholder' => '+91 98765 43210'],
                                ['name' => 'company', 'label' => 'Company name', 'type' => 'text',  'autocomplete' => 'organization', 'placeholder' => 'Your business'],
                            ] as $f)
                                <div>
                                    <label for="join-{{ $f['name'] }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $f['label'] }}</label>
                                    <input id="join-{{ $f['name'] }}" name="{{ $f['name'] }}" type="{{ $f['type'] }}" required
                                           autocomplete="{{ $f['autocomplete'] }}" placeholder="{{ $f['placeholder'] }}" value="{{ old($f['name']) }}"
                                           @error($f['name']) aria-invalid="true" aria-describedby="join-{{ $f['name'] }}-error" @enderror
                                           class="{{ $field }} {{ $errors->has($f['name']) ? 'border-red-400' : 'border-gray-300' }}">
                                    @error($f['name'])<p id="join-{{ $f['name'] }}-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <label for="join-vertical" class="block text-sm font-medium text-gray-700 mb-1">Vertical</label>
                            <select id="join-vertical" name="vertical" required
                                    @error('vertical') aria-invalid="true" aria-describedby="join-vertical-error" @enderror
                                    class="{{ $field }} bg-white {{ $errors->has('vertical') ? 'border-red-400' : 'border-gray-300' }}">
                                <option value="">Choose your industry</option>
                                @foreach(CommunityMember::VERTICALS as $vertical)
                                    <option value="{{ $vertical }}" @selected(old('vertical') === $vertical)>{{ $vertical }}</option>
                                @endforeach
                            </select>
                            @error('vertical')<p id="join-vertical-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <fieldset @error('expectations') aria-describedby="join-expectations-error" @enderror>
                            <legend class="block text-sm font-medium text-gray-700 mb-2">What do you expect from BG Community?</legend>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach(CommunityMember::EXPECTATIONS as $key => $label)
                                    <label class="flex items-center gap-3 rounded-lg border border-gray-200 px-3 py-2.5 text-sm cursor-pointer hover:border-brand-gold has-[:checked]:border-brand-gold has-[:checked]:bg-brand-gold/5">
                                        <input type="checkbox" name="expectations[]" value="{{ $key }}" @checked(in_array($key, $chosen, true))
                                               class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            @error('expectations')<p id="join-expectations-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            @error('expectations.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </fieldset>

                        <div>
                            <label for="join-message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-gray-400 font-normal">(optional)</span></label>
                            <textarea id="join-message" name="message" rows="4" maxlength="2000"
                                      placeholder="Tell us what your business does and who you'd like to meet."
                                      class="{{ $field }} {{ $errors->has('message') ? 'border-red-400' : 'border-gray-300' }}">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full rounded-full bg-brand-gold hover:bg-yellow-500 text-brand-dark font-bold py-3.5 transition-colors">
                            Register &amp; verify my email
                        </button>
                        <p class="text-xs text-gray-500 text-center">
                            We'll email you a link to confirm your address. Your details are only used for BG Community —
                            see our <a href="{{ route('privacy-policy') }}" class="underline">privacy policy</a>.
                        </p>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
