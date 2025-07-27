@php
    $contacts = [
        [
            'icon' => 'ri-messenger-line',
            'text' => 'Contact us',
            'url' => '#',
        ],
        [
            'icon' => 'ri-mail-fill',
            'text' => 'hello@imotion.my',
            'url' => 'mailto:hello@imotion.my',
        ]
    ];

    $socials = [
        [
            'icon' => 'ri-linkedin-box-fill',
            'url' => '#',
        ],
        [
            'icon' => 'ri-instagram-line',
            'url' => '#',
        ],
        [
            'icon' => 'ri-youtube-line',
            'url' => '#',
        ],
        [
            'icon' => 'ri-facebook-fill',
            'url' => '#',
        ],
    ];

    $navigation = [
        [
            'text' => 'Home',
            'active' => 'home',
            'url' => route('home'),
        ],
        [
            'text' => 'About',
            'active' => 'about',
            'url' => '#',
        ],
        [
            'text' => 'Services',
            'active' => 'service.*',
            'children' => [
                [
                    'text' => 'Digital Marketing',
                    'active' => 'service.digital-marketing',
                    'url' => route('service.digital-marketing'),
                ],
                [
                    'text' => 'Printing Design',
                    'active' => 'service.printing-design',
                    'url' => route('service.printing-design'),
                ],
                [
                    'text' => 'Web Development',
                    'active' => 'service.web-development',
                    'url' => route('service.web-development'),
                ],
                [
                    'text' => 'Mobile Application',
                    'active' => 'service.mobile-application',
                    'url' => route('service.mobile-application'),
                ],
                [
                    'text' => 'Graphic Design',
                    'active' => 'service.graphic-design',
                    'url' => route('service.graphic-design'),
                ],
            ]
        ],
        [
            'text' => 'Portfolio',
            'active' => 'portfolio',
            'url' => '#',
        ],
        [
            'text' => 'Contact',
            'active' => 'contact',
            'url' => '#',
        ],
    ];
@endphp

<div class="w-full bg-background">
    <div class="container flex flex-col md:flex-row items-center justify-between px-4 md:px-6 py-2 text-sm text-accent-2 gap-2 md:gap-0">
        <div class="flex items-center gap-4 w-full md:w-auto justify-center md:justify-start">
            @foreach ($contacts as $contact)
                <a href="{{ $contact['url'] }}" class="flex items-center hover:text-accent-2-hover hover:underline underline-offset-2 gap-1.5 transition-colors duration-300">
                    <x-icon name="{{ $contact['icon'] }}" class="size-4 text-current shrink-0"></x-icon>
                    <div>{{ $contact['text'] }}</div>
                </a>
            @endforeach
        </div>
        <div class="flex items-center gap-4 w-full md:w-auto justify-center md:justify-end">
            <div>English</div>
            <div class="flex items-center gap-2">
                @foreach ($socials as $social)
                    <a href="{{ $social['url'] }}" class="text-accent-2 p-1 border border-accent-2/50 hover:bg-foreground/10 rounded-tl-lg rounded-br-lg hover:text-accent-2-hover transition-colors duration-300">
                        <x-icon name="{{ $social['icon'] }}" class="size-5"></x-icon>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<header class="w-full bg-background border-b border-yellow-400/10 sticky top-0 z-40">
    <div class="container flex items-center justify-between py-3 px-4 md:px-0" x-data="menu()">
        @if (!request()->routeIs('home'))
        <a href="{{ route('home') }}">
            <x-logo class="h-10 w-auto text-foreground" />
        </a>
        @else
        <div>
            <x-logo class="h-10 w-auto text-foreground" />
        </div>
        @endif
        <button type="button" class="block cursor-pointer lg:hidden" @click="toggle">
            <x-icon name="ri-menu-fill" class="text-foreground size-7 block" />
        </button>
        <div
            x-ref="overlay"
            class="invisible fixed inset-0 z-99 bg-black/30 opacity-0 transition-colors duration-300"
            :class="{ 'block opacity-100 visible': isOpen }"
            @click="toggle"
        ></div>

        <nav
            x-ref="menu"
            class="invisible fixed top-0 -left-60 z-99 h-full w-80 opacity-0 transition-[opacity,visibility,left] duration-300 bg-white dark:bg-slate-900 lg:dark:bg-transparent lg:bg-transparent lg:visible lg:relative lg:left-0 lg:w-auto lg:opacity-100"
            :class="{ 'left-0 opacity-100 visible': isOpen }"
        >
            <button type="button" class="absolute top-4 right-4 block cursor-pointer lg:hidden" @click="toggle">
                <x-icon name="ri-close-fill" class="text-foreground size-7 block" />
            </button>
            <div class="flex flex-col items-center lg:flex-col-reverse lg:items-end gap-6 lg:gap-2">
                <ul class="mt-14 lg:mt-0 divide-y lg:divide-none divide-border w-full">
                    @foreach ($navigation as $menu)
                        @if (isset($menu['children']) && count($menu['children']) > 0)
                        <li x-ref="wrap" class="group relative block lg:inline-block" x-data="subMenu()">
                            <button type="button" @click="toggle" class="text-foreground lg:text-foreground hover:text-accent-2 block px-4 py-3 lg:px-2 lg:py-1 font-semibold transition-color duration-300 {{ request()->routeIs($menu['active']) ? 'text-accent-2!' : '' }} flex items-center justify-between gap-2 w-full lg:w-auto">
                                {{ $menu['text'] }}
                                <x-icon name="ri-arrow-down-s-line" class="size-5 block stroke-current lg:group-hover:rotate-180 transition-transform duration-300" x-bind:class="{ 'rotate-180': isOpen }" />
                            </button>
                            <ul x-ref="list" class="border-border p-0 m-0 visible relative top-0 left-0 lg:top-full lg:-left-20 max-h-0 w-full overflow-hidden border-none bg-transparent opacity-100 shadow-none transition-color duration-300 outline-none group-hover:visible group-hover:block group-hover:transform-none group-hover:opacity-100 lg:invisible lg:absolute! lg:w-72 lg:bg-background lg:opacity-0 lg:shadow-lg lg:max-h-none lg:py-3 lg:mt-2">
                                @foreach ($menu['children'] as $subMenu)
                                <li class="block">
                                    <a href="{{ $subMenu['url'] }}" class="block pl-8 pr-4 py-3 lg:px-5 lg:py-2 font-semibold text-foreground hover:text-accent-2 transition-color duration-300 lg:hover:underline lg:underline-offset-2 {{ request()->routeIs($subMenu['active']) ? 'text-accent-2!' : '' }}">
                                        {{ $subMenu['text'] }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="relative my-0 block lg:mx-2 lg:inline-block">
                            <a href="{{ $menu['url'] }}" class="text-foreground lg:text-foreground hover:text-accent-2 block px-4 py-3 lg:px-2 lg:py-1 font-semibold transition-color duration-300 {{ request()->routeIs($menu['active']) ? 'text-accent-2!' : '' }}">
                                {{ $menu['text'] }}
                            </a>
                        </li>
                        @endif
                    @endforeach
                </ul>
                <div x-ref="mobileElement" class="block lg:hidden"></div>
            </div>
        </nav>

        <div class="hidden lg:block">
            <div class="flex items-center gap-4" x-ref="element">
                <button type="button" x-data="toggleTheme()" class="inline-flex items-center justify-center p-2 cursor-pointer text-foreground" aria-label="Toggle dark mode" x-on:click="toggle()">
                    <x-icon name="ri-sun-fill" class="size-5 scale-0 rotate-90 transition-transform duration-500 ease-in-out dark:scale-100 dark:rotate-0"></x-icon>
                    <x-icon name="ri-moon-fill" class="size-5 absolute scale-100 rotate-0 transition-transform duration-500 ease-in-out dark:scale-0 dark:-rotate-90"></x-icon>
                </button>
                <a href="#" class="flex items-center justify-center gap-2 px-4 py-2 font-semibold text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                    <x-icon name="ri-login-box-line" class="size-5" />
                    <span>Login</span>
                </a>
            </div>
        </div>
    </div>
</header>
