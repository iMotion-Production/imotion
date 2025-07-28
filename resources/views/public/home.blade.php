@extends('public/layouts/base')

@php
    $services = [
        [
            'title' => 'Graphic Design',
            'icon' => 'ri-customer-service-2-fill',
            'description' => 'Craft a powerful brand identity with stunning visuals that communicate your story and stand out in any market.  ',
            'link' => '#'
        ],
        [
            'title' => 'Digital Marketing',
            'icon' => 'ri-government-fill',
            'description' => 'Grow your online presence with strategic campaigns, engaging content, and proven SEO techniques that boost visibility and conversions.',
            'link' => '#'
        ],
        [
            'title' => 'Social Media Management',
            'icon' => 'ri-pushpin-2-fill',
            'description' => 'Strategic content creation, scheduling, and engagement to grow your online community.',
            'link' => '#'
        ],
        [
            'title' => 'Website Design & Development',
            'icon' => 'ri-pen-nib-line',
            'description' => 'Build a fast, responsive, and visually appealing website that reflects your brand and business goals.',
            'link' => '#'
        ],
        [
            'title' => 'Video Advertising',
            'icon' => 'ri-play-circle-line',
            'description' => 'Capture audiences with high-impact video ads, animated explainers, and immersive stories for digital and social media.',
            'link' => '#'
        ],
        [
            'title' => 'Mobile App Design & Developments',
            'icon' => 'ri-smartphone-line',
            'description' => 'Create intuitive, user-friendly mobile apps that deliver seamless experiences across iOS and Android platforms.',
            'link' => '#'
        ]
    ];

    $choseUs = [
        [
            'title' => '24/7 Help desk Availability',
            'description' => 'We provide our clients with direct access to a dedicated team.',
        ],
        [
            'title' => 'Risk-Free Satisfaction',
            'description' => 'We pour our combined knowledge and experience into every project.',
        ],
        [
            'title' => 'Fresh Business Thinking',
            'description' => 'Innovate your business strategy with fresh thinking that drives innovation.',
        ],
    ];

    $experiences = [
        [
            "value" => "90+",
            "label" => "Clients"
        ],
        [
            "value" => "400+",
            "label" => "Designs"
        ],
        [
            "value" => "500+",
            "label" => "Projects"
        ],
        [
            "value" => "30+",
            "label" => "Team Member"
        ]
    ];
@endphp

@section('content')
<main class="bg-background overflow-x-hidden">
    <section class="container py-10 sm:py-14 lg:py-24">
        <div class="flex flex-col-reverse lg:flex-row gap-16 items-center justify-evenly">
            <div class="text-center lg:text-left text-foreground max-w-3xl">
                <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                    FutureProof Digital Marketing Agency
                </span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold uppercase mt-3">
                    iMotion Production Creative Design & Digital Marketing Agency
                </h1>
                <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted">
                    From digital strategy, specialize in SEO - We Craft Digital Experiences That Engage, Convert, and Grow Your Business.
                </p>
                <div class="mt-5 sm:mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-2 sm:gap-4">
                    <a href="#" class="flex items-center justify-center gap-2 py-3 px-6 font-semibold text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                        <span>Get Started</span>
                        <x-icon name="ri-arrow-right-line" class="size-5" />
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 py-3 px-6 border border-accent-2 font-semibold text-foreground bg-transparent-1 hover:bg-accent-2 hover:text-background rounded-tl-xl rounded-br-xl transition-colors duration-300">
                        <span>Contact Us</span>
                        <x-icon name="ri-messenger-line" class="size-5" />
                    </a>
                </div>
            </div>
            <img class="w-full h-auto max-w-lg" src="/assets/home/hero.png" alt="Illustration of FutureProof Digital Marketing Agency">
        </div>
    </section>

    <section class="py-10 sm:py-14 lg:py-20">
        <div class="container">
            <h2 class="text-3xl text-center font-bold text-foreground">They trust Us</h2>
            <div class="mt-8 sm:mt-12 flex items-center justify-center gap-6 sm:gap-10 flex-wrap">
                @for ($index = 1; $index <= 34; $index++)
                    <img src="/assets/home/logo/{{ $index }}.png" loading="lazy" alt="Logo {{ $index }}" class="h-16 w-auto invert-100 dark:invert-0">
                @endfor
            </div>
        </div>
    </section>

    <section class="container py-10 sm:py-14 lg:py-24">
        <div class="flex flex-col lg:flex-row gap-16 items-center justify-evenly">
            <img class="w-full h-auto max-w-xl" src="/assets/home/about.png" alt="Illustration of Designed to Drive Results">
            <div class="text-left text-foreground max-w-2xl">
                <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                    About Us
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mt-3">
                    Designed to Drive Results
                </h1>
                <div class="mt-3 sm:mt-6 space-y-3 sm:space-y-4 text-base sm:text-lg text-muted">
                    <p>
                        Specializing in Graphic Design, Digital Marketing, SEO Management, Social Media Design, and Video Animation & 3D, we empower startups, entrepreneurs, and corporate leaders to communicate with impact.
                    </p>
                    <p>
                        Whether you're launching a new brand, promoting an event, or subtly your existing marketing materials, we are driven by our passion to move your business forward - creatively and strategically.
                    </p>
                </div>
                <ul class="mt-3 sm:mt-6 space-y-2 sm:space-y-3 text-muted">
                    <li class="flex items-start gap-3">
                        <span class="text-accent-2">
                            <x-icon name="ri-check-line" class="size-5" />
                        </span>
                        <span>Value for Money</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-accent-2">
                            <x-icon name="ri-check-line" class="size-5" />
                        </span>
                        <span>After Sales support</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-accent-2">
                            <x-icon name="ri-check-line" class="size-5" />
                        </span>
                        <span>Clients Focused</span>
                    </li>
                </ul>
                <div class="mt-5 sm:mt-8">
                    <a href="#" class="inline-flex items-center justify-center gap-2 py-3 px-6 font-semibold text-background bg-accent-1 hover:bg-accent-1-hover rounded-tl-xl rounded-br-xl transition-colors duration-300">
                        <span>View More</span>
                        <x-icon name="ri-arrow-right-line" class="size-5" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-16 lg:py-24">
        <div class="text-center mx-auto text-foreground max-w-7xl">
            <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                What We Do
            </span>
            <h1 class="text-4xl md:text-5xl font-bold mt-3">
                Our Creative & Digital Marketing Services
            </h1>
            <p class="mt-3 sm:mt-6 space-y-3 sm:space-y-4 text-base sm:text-lg text-muted max-w-3xl mx-auto">
                We are a powerhouse of dedicated experts and creators in all modern fields of Digital Marketing. We empower your business using the most effective tools in combination with proven strategies, creative magic and data-driven assets, we love the leeway to take your business to the next level and beyond.
            </p>
        </div>
         <div class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($services as $service)
                <div class="bg-background border border-border rounded-2xl p-8 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="w-16 h-16 rounded-full bg-accent-2/10 dark:bg-accent-2/20 text-accent-2 mx-auto flex items-center justify-center">
                        <x-icon name="{{ $service['icon'] }}" class="size-8" />
                    </div>
                    <h3 class="text-xl font-bold mt-6 text-foreground">{{ $service['title'] }}</h3>
                    <p class="mt-2 text-muted text-sm leading-relaxed">
                        {{ $service['description'] }}
                    </p>
                    <a href="{{ $service['link'] }}" class="inline-flex items-center gap-1.5 mt-6 text-sm font-semibold text-muted hover:text-accent-1 transition-colors">
                        <span>Read More</span>
                        <x-icon name="ri-arrow-right-line" class="size-4" />
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section x-data="videoModal()" class="relative">
        <div class="container py-16 lg:py-24">
            <div class="text-left text-foreground max-w-4xl">
                <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                    Why Choose Us
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mt-3">
                    Our Digital Agency Delivers Extraordinary Business Value.
                </h1>
                <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-3xl">
                    Our continuous automation of receptive processes and use of intelligent tools in product development enable us to deliver within a short timeframe and increase the time to value ratio.
                </p>
            </div>

            <div class="relative z-10 mt-12 lg:mt-16 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="relative w-full max-w-md aspect-square rounded-tl-4xl rounded-br-4xl overflow-hidden border border-border mx-auto">
                    <img src="/assets/home/video-thumb.jpg" alt="Video Placeholder" class="w-full h-full object-cover">
                    <div class="absolute inset-0 w-full h-full flex items-center justify-center">
                        <button @click="openModal()" class="p-4 rounded-full bg-background/50 text-accent-2 hover:bg-accent-2 hover:text-background backdrop-blur-sm border border-border transition-colors duration-300 cursor-pointer">
                            <x-icon name="ri-play-fill" class="size-10 text-current" />
                        </button>
                    </div>
                </div>

                <div class="space-y-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-foreground">
                        Proactively - with a transparent process to uphold both your deadlines & your budget
                    </h2>
                    <p class="mt-3 sm:mt-6 text-base sm:text-lg text-muted max-w-3xl">
                        Our approach to steering digital products and services is grounded in strategy, design, data and innovation. We work in close-knit, efficient teams that allow us to be nimble when tackling some of the biggest and most complex business challenges, defining culture and creating brand desire.
                    </p>
                    <div class="space-y-4 pt-4">
                        @foreach ($choseUs as $item)
                            <div class="flex items-start gap-4">
                                <div class="size-8 rounded-full bg-accent-2 text-background flex-shrink-0 flex items-center justify-center">
                                    <x-icon name="ri-check-line" class="size-5" />
                                </div>
                                <div>
                                    <h4 class="text-xl sm:text-2xl font-bold text-foreground">{{ $item['title'] }}</h4>
                                    <p class="text-base sm:text-lg text-muted mt-1">{{ $item['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <img src="/assets/home/decorator-mobile.png" alt="Decorator mobile" aria-hidden="true" class="absolute -top-28 -right-32 w-[700px] pointer-events-none z-0 hidden xl:block">

        <div x-cloak x-show="popupOpen" @keydown.escape.window="closeModal()" class="fixed inset-0 z-50 flex items-center justify-center bg-background/80 backdrop-blur-sm p-4" aria-modal="true">
            <div x-show="popupOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" @click.away="closeModal()" class="relative w-full max-w-4xl bg-background rounded-xl shadow-2xl p-3 sm:p-5">
                <div class="aspect-video">
                    <video x-ref="videoPlayer" controls class="w-full h-full">
                        <source src="/assets/home/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <button @click="closeModal()" aria-label="Close" class="absolute -top-10 sm:-top-6 lg:-top-4 right-0 lg:-right-4 size-10 bg-accent-2 rounded-full text-background flex items-center justify-center transition-colors cursor-pointer hover:bg-accent-2-hover">
                    <x-icon name="ri-close-line" class="size-6" />
                </button>
            </div>
        </div>
    </section>

    <section class="container py-16 lg:py-24">
        <div class="text-center mx-auto text-foreground max-w-7xl">
            <span class="inline-block bg-accent-1 text-sm font-semibold text-background py-1 px-3 rounded-tl-lg rounded-br-lg">
                Our Experience
            </span>
            <h1 class="text-4xl md:text-5xl font-bold mt-3">
                A Proven 10-Year History Of Success In Creative Digital Marketing Technology
            </h1>
        </div>
        <div class="mt-10 sm:mt-16 grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 items-start">
            @foreach ($experiences as $idx => $experience)
                <div class="bg-foreground/10 border border-border p-8 rounded-lg text-center w-full {{ $idx % 2 == 0 ? 'md:mt-8' : '' }}">
                    <p class="text-3xl lg:text-4xl font-extrabold text-accent-1">{{ $experience['value'] }}</p>
                    <div class="mt-2 text-lg sm:text-xl text-foreground font-semibold">{{ $experience['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
