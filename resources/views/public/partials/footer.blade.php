@php
    $footers = [
        [
            'text' => 'Home',
            'url' => route('home'),
        ],
        [
            'text' => 'Printing Design',
            'url' => route('service.printing-design'),
        ],
        [
            'text' => 'About',
            'url' => route('about'),
        ],
        [
            'text' => 'Shop',
            'url' => '#',
        ],
        [
            'text' => 'Graphic Design',
            'url' => route('service.graphic-design'),
        ],
        [
            'text' => 'Contact',
            'url' => route('contact'),
        ],
        [
            'text' => 'Digital Marketing',
            'url' => route('service.digital-marketing'),
        ],
        [
            'text' => 'Printing Partnership',
            'url' => '#',
        ],
        [
            'text' => 'Website Design & Dev',
            'url' => route('service.web-development'),
        ],
        [
            'text' => 'News Community',
            'url' => '#',
        ],
        [
            'text' => 'Mobile Application Dev',
            'url' => route('service.mobile-application'),
        ],
    ];

    $footerMenu = [
        [
            'text' => 'Terms of Service',
            'url' => '#',
        ],
        [
            'text' => 'Privacy Policy',
            'url' => '#',
        ],
        [
            'text' => 'Cookie Policy',
            'url' => '#',
        ],
    ];

    $socials = [
        [
            'icon' => 'ri-linkedin-box-fill',
            'url' => 'https://linkedin.com/company/imotion-production',
        ],
        [
            'icon' => 'ri-instagram-line',
            'url' => 'https://instagram.com/imotion_production',
        ],
        [
            'icon' => 'ri-youtube-line',
            'url' => 'https://youtube.com/@imotion.production',
        ],
        [
            'icon' => 'ri-facebook-fill',
            'url' => 'https://facebook.com/imotionshack',
        ],
        [
            'icon' => 'ri-pinterest-fill',
            'url' => 'https://pinterest.com/imotionproduction',
        ],
        [
            'icon' => 'ri-github-fill',
            'url' => 'https://github.com/nadz87',
        ],
        [
            'icon' => 'ri-behance-fill',
            'url' => 'https://behance.net/imotionpro',
        ],
        [
            'icon' => 'ri-threads-line',
            'url' => 'https://threads.com/@imotion_production',
        ],
        [
            'icon' => 'ri-telegram-2-line',
            'url' => 'https://t.me/iMotionz',
        ],
    ];
@endphp

<footer class="relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="/assets/public/bg-footer.jpg" alt="Background Footer" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/85"></div>
    </div>
    <div class="relative z-10">
        <div class="container py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8">
                <div class="md:col-span-2 lg:col-span-4 text-white">
                    @if (!request()->routeIs('home'))
                    <a href="{{ route('home') }}">
                        <x-logo class="h-14 w-auto text-white" />
                    </a>
                    @else
                    <div>
                        <x-logo class="h-14 w-auto text-white" />
                    </div>
                    @endif
                    <p class="mt-2 text-sm font-semibold">Creation Digital Marketing Agency</p>
                    <p class="mt-4 text-sm leading-relaxed text-white/80">
                        Elevate your brand with expert Graphic Design, Digital Marketing, SEO Management, Social Media Design, Video Animation & 3D, and Corporate Video production. We empower startups, founders, and corporate leaders to communicate with impact - creatively, strategically, and memorably.
                    </p>
                    <div class="mt-6 space-y-4 text-sm text-white/80">
                        <a href="https://maps.app.goo.gl/yqkTXCtVCVUt9A7K7" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 hover:text-accent-1 hover:underline underline-offset-2 transition-colors duration-300">
                            <x-icon name="ri-map-pin-2-fill" class="size-5 flex-shrink-0" />
                            <span>Wisma Suria, Cyber 5 63000, Cyberjaya</span>
                        </a>
                        <a href="mailto:connect@imotion.my" class="flex items-center gap-3 hover:text-accent-1 hover:underline underline-offset-2 transition-colors duration-300">
                            <x-icon name="ri-mail-fill" class="size-5 flex-shrink-0" />
                            <span>connect@imotion.my</span>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-4 text-white">
                    <h3 class="text-xl">Company Links</h3>
                    <div class="w-16 h-1 rounded-md bg-accent-1/50 mt-1"></div>
                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-white/80">
                        @foreach ($footers as $footer)
                            <a href="{{ $footer['url'] }}" class="hover:text-accent-1 hover:underline underline-offset-2 transition-colors duration-300">{{ $footer['text'] }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-4 text-white">
                    <h3 class="text-xl">Newsletter</h3>
                    <div class="w-16 h-1 rounded-md bg-accent-1/50 mt-1"></div>
                    <p class="mt-5 text-sm text-white/80">
                        Subscribe and stay up to date with the latest our tips and news.
                    </p>
                    <form class="mt-4 flex items-center w-full relative">
                        <input type="email" placeholder="Email" class="w-full rounded-tl-2xl rounded-br-2xl border-accent-1/30 bg-white p-4 pr-32 text-sm text-slate-900 placeholder:text-slate-900/60 focus:border-accent-1 focus:ring-0">
                        <button type="submit" class="absolute right-2 rounded-tl-xl rounded-br-xl bg-accent-1 px-5 py-2 text-sm font-semibold text-slate-900 hover:bg-accent-1-hover transition-colors">
                            Subscribe
                        </button>
                    </form>
                    <div class="mt-6 flex items-center flex-wrap gap-3">
                        @foreach ($socials as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" class="text-white p-2 border border-current/20 hover:bg-white/10 rounded-tl-md rounded-br-md hover:border-accent-2-hover transition-colors duration-300">
                                <x-icon name="{{ $social['icon'] }}" class="size-5"></x-icon>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-white/20">
            <div class="container py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-white/80">
                <p class="text-center md:text-left">
                    {{ config('app.name') }} Copyright {{ date('Y') }}. All rights reserved.
                </p>
                <div class="flex flex-wrap justify-center items-center gap-x-6 gap-y-2">
                    @foreach ($footerMenu as $footer)
                        <a href="{{ $footer['url'] }}" class="hover:text-accent-1 hover:underline underline-offset-2 transition-colors duration-300">{{ $footer['text'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>
